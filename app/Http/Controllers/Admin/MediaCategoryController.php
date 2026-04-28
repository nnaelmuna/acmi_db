<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class MediaCategoryController extends Controller

{
    // GET list category
    public function index()
    {
        $categories = MediaCategory::latest()->get();
        return view('media.categories', compact('categories'));
    }

    // STORE (Add Category)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MediaCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Category added successfully');
    }

    // UPDATE (Edit Category)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = MediaCategory::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Category updated successfully');
    }

    // DELETE
    public function destroy($id)
    {
        $category = MediaCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Category deleted successfully');
    }
}