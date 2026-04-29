<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:categories,name']);
        
        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
    
        return redirect()->back()->with('success', 'Category added!');
    }
    
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
}
