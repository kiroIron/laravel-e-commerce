<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;      // Eloquent Cart model
use App\Models\CartItem;  // Eloquent CartItem model
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Add a product to the database-persisted cart.
     * Endpoint: POST /api/cart
     *
     * Expected JSON Body:
     * {
     *   "user_id": 1,
     *   "product_id": 2,
     *   "qty": 3
     * }
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1'
        ]);

        // Find or create a Cart record for the given user.
        $cart = Cart::firstOrCreate([
            'user_id' => $validatedData['user_id']
        ]);

        // Update or create the cart item.
        // If the item already exists, increment the quantity.
        $cart->items()->updateOrCreate(
            ['product_id' => $validatedData['product_id']],
            ['quantity' => DB::raw("COALESCE(quantity, 0) + {$validatedData['qty']}")]
        );

        return response()->json(['message' => 'Product added to cart (DB)'], 201);
    }

    /**
     * Retrieve all items from a user's database cart.
     * Endpoint: GET /api/cart
     *
     * Expected Query Parameter:
     * ?user_id=1
     */
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $cart = Cart::where('user_id', $validatedData['user_id'])->with('items')->first();

        // Return an empty array if no cart exists.
        if (!$cart) {
            return response()->json([], 200);
        }

        return response()->json($cart->items, 200);
    }

    /**
     * Update the quantity of a specific cart item.
     * Endpoint: PUT /api/cart/{cart_item_id}
     *
     * Expected JSON Body:
     * {
     *   "qty": 5
     * }
     *
     * Note: The {cart_item_id} is the ID of the cart item (from cart_items table).
     */
    public function update(Request $request, $cart_item_id)
    {
        $validatedData = $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($cart_item_id);
        $cartItem->update(['quantity' => $validatedData['qty']]);

        return response()->json(['message' => 'Cart item updated (DB)'], 200);
    }

    /**
     * Remove a specific cart item.
     * Endpoint: DELETE /api/cart/{cart_item_id}
     */
    public function destroy($cart_item_id)
    {
        $cartItem = CartItem::findOrFail($cart_item_id);
        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed (DB)'], 200);
    }

    /**
     * Clear the entire cart for a user.
     * Endpoint: POST /api/cart/clear
     *
     * Expected JSON Body:
     * {
     *   "user_id": 1
     * }
     */
    public function clear(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $cart = Cart::where('user_id', $validatedData['user_id'])->first();
        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json(['message' => 'Cart cleared (DB)'], 200);
    }
}
