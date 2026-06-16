<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:product_categories,name']);

        $category = ProductCategory::create(['name' => $request->name]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'category' => $category]);
        }

        return redirect()->back()->with('success', 'Category added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name,' . $id
        ]);

        $category = ProductCategory::findOrFail($id);
        $category->update(['name' => $request->name]);

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true]);
    }
}