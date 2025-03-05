<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;       // Your Eloquent Cart model
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Retrieve all orders for the authenticated user.
     */
    public function index()
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $orders = Order::where('user_id', $userId)->get();
        return response()->json($orders, 200);
    }

    /**
     * Create a new order using the database-persisted cart contents.
     * Endpoint: POST /api/orders
     *
     * Expected JSON Body:
     * {
     *    "city": "New York",
     *    "address": "5th Avenue",
     *    "building_number": "101",
     *    "payment_method": "stripe"
     * }
     */
    public function store(Request $request)
    {
        // Validate delivery address and payment method if provided.
        $validatedData = $request->validate([
            'city'            => 'required|string|max:255',
            'address'         => 'required|string|max:255',
            'building_number' => 'required|string|max:50',
            'payment_method'  => 'nullable|string|in:stripe,paypal'
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve the DB-based cart for the authenticated user, including related cart items.
        $cart = Cart::where('user_id', $userId)
            ->with('items.product') // Assuming each CartItem has a 'product' relation
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Map cart items to a simple products array.
        // We assume that each CartItem has a 'product' relation with attributes 'name' and 'price'.
        $products = $cart->items->map(function ($item) {
            return [
                'id'    => $item->product_id,
                'name'  => $item->product->name,
                'qty'   => $item->quantity,
                'price' => $item->product->price,
            ];
        })->values();

        // Calculate total price.
        $totalPrice = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Generate a unique order number.
        $orderNumber = strtoupper(uniqid('ORDER'));

        // Create the order record.
        $order = Order::create([
            'user_id'         => $userId,
            'order_number'    => $orderNumber,
            'products'        => $products,
            'total_price'     => $totalPrice,
            'city'            => $validatedData['city'],
            'address'         => $validatedData['address'],
            'building_number' => $validatedData['building_number'],
            'payment_method'  => $validatedData['payment_method'] ?? null,
            // 'status' and 'payment_status' will default as defined in your migration/model.
        ]);

        // Clear the cart items after creating the order.
        $cart->items()->delete();

        return response()->json($order, 201);
    }

    /**
     * Retrieve order details by ID.
     * Endpoint: GET /api/orders/{id}
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return response()->json($order, 200);
    }

    /**
     * Update order status.
     * Endpoint: PUT /api/orders/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validatedData = $request->validate([
            'status' => 'required|string|in:pending,shipped,delivered'
        ]);

        $order->status = $validatedData['status'];
        $order->save();

        return response()->json($order, 200);
    }

    /**
     * Delete an order.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
