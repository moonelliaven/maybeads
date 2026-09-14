<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => ['required', 'string', 'max:100'],
            'image' => ['required', 'string', 'max:50'],
        ]);

        return Category::create($validated);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'category_name' => ['sometimes', 'string', 'max:100'],
            'image' => ['sometimes', 'string', 'max:50'],
        ]);

        $category->update($validated);

        return $category;
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]); 
    }
}
