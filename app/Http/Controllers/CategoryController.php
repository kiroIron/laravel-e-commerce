<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        // The Category model's accessors ensure only localized data is returned.
        return response()->json(Category::all(), 200);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // When storing, expect both language versions if available.
            'name_en'        => 'required|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image'          => 'nullable|url',
            'status'         => 'boolean'
        ]);

        $category = Category::create($validatedData);

        return response()->json($category, 201);
    }

    /**
     * Display the specified category.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validatedData = $request->validate([
            'name_en'        => 'sometimes|required|string|max:255',
            'name_ar'        => 'sometimes|nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image'          => 'nullable|url',
            'status'         => 'boolean'
        ]);

        $category->update($validatedData);

        return response()->json($category, 200);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
