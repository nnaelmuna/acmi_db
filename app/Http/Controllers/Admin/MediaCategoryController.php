<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaCategory;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = MediaCategory::orderBy('is_default', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        $allMedia = MediaItem::with('category')->latest()->get();

        $counts = MediaItem::with('category')
            ->get()
            ->groupBy(fn($item) => $item->category->name ?? '-')
            ->map(fn($items) => $items->count())
            ->toArray();

        $media = MediaItem::with('category')
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('name', $request->category);
                });
            })
            ->latest()
            ->get();

        return view('media', compact('media', 'allMedia', 'categories', 'counts'));
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
        $category->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ]);
        }

        return redirect()->route('media')->with('success', 'Category deleted successfully.');
    }
}
