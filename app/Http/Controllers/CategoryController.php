<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::create($request->all());
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($request->all());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function showByParent()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return CategoryResource::collection($categories);
    }

    public function getCategoryTree()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return CategoryResource::collection($categories);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.order' => 'required|integer',
        ]);

        foreach ($request->categories as $category) {
            Category::where('id', $category['id'])->update(['order' => $category['order']]);
        }

        return response()->json(['message' => 'Categories reordered successfully']);
    }

    public function showCategoryWithChildren(Category $category)
    {
        return new CategoryResource($category->load('children'));
    }

    public function getProducts(Category $category)
    {
        return response()->json($category->products);
    }
} 