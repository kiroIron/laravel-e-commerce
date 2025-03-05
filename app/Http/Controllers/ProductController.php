<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a paginated listing of the products.
     */
    public function index()
    {
        return response()->json(Product::paginate(10), 200);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data for localization
        $validatedData = $request->validate([
            'name_en'             => 'required|string|max:255',
            'name_ar'             => 'nullable|string|max:255',
            'description_en'      => 'nullable|string',
            'description_ar'      => 'nullable|string',
            'images'              => 'nullable|array',
            'images.*'            => 'nullable|url',
            'price'               => 'required|numeric|min:0',
            'discounted_price'    => 'nullable|numeric|min:0',
            'quantity'            => 'required|integer|min:0',
            'status'              => 'boolean',
            'category_id'         => 'required|exists:categories,id'
        ]);

        // Create the product using the validated data.
        $product = Product::create($validatedData);

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validatedData = $request->validate([
            'name_en'             => 'sometimes|required|string|max:255',
            'name_ar'             => 'sometimes|nullable|string|max:255',
            'description_en'      => 'nullable|string',
            'description_ar'      => 'nullable|string',
            'images'              => 'nullable|array',
            'images.*'            => 'nullable|url',
            'price'               => 'sometimes|required|numeric|min:0',
            'discounted_price'    => 'nullable|numeric|min:0',
            'quantity'            => 'sometimes|required|integer|min:0',
            'status'              => 'boolean',
            'category_id'         => 'sometimes|required|exists:categories,id'
        ]);

        $product->update($validatedData);

        return response()->json($product, 200);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
