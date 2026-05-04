<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaCategoryController extends Controller
{
    public function index()
    {
        $categories = MediaCategory::orderBy('is_default', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        return view('media.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:media_categories,name',
        ]);
    
        $category = MediaCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_default' => 0,
        ]);
    
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'category' => $category]);
        }
    
        return back()->with('success', 'Category added successfully');
    }

    public function update(Request $request, $id)
    {
        $category = MediaCategory::findOrFail($id);

        if ($category->is_default) {
            return back()->with('success', 'Default category cannot be edited');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:media_categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = MediaCategory::findOrFail($id);
        $name = $category->name;
        $category->delete();

        return response()->json(['success' => true]);
    }
}
