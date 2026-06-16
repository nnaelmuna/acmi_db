<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100|unique:categories,name'
            ]);
            
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'category' => $category,
                ]);
            }
        
            return redirect()->back()->with('success', 'Category added!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['name'][0] ?? 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add category. It might already exist (even if deleted).'
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        $category->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}