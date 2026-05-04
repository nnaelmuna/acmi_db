<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Models\MediaCategory;
use Illuminate\Http\Request;

class MediaItemController extends Controller
{
    // LIST MEDIA
    public function index(Request $request)
{
    $categories = MediaCategory::orderBy('is_default', 'desc')
        ->orderBy('id', 'asc')
        ->get();

    $allMedia = MediaItem::with('category')->get();

    $query = MediaItem::with('category');

    if ($request->filled('category')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('slug', $request->category);
        });
    }

    $media = $query->latest()->get();

    $counts = $categories->mapWithKeys(function ($cat) use ($allMedia) {
        return [$cat->slug => $allMedia->where('media_category_id', $cat->id)->count()];
    })->toArray();

    return view('media', compact('media', 'categories', 'allMedia'));
}

    // STORE (Add Media)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'media_category_id' => 'required|exists:media_categories,id',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // upload image
        $imagePath = $request->file('image')->store('media', 'public');

        MediaItem::create([
            'title' => $request->title,
            'media_category_id' => $request->media_category_id,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Media added successfully');
    }

    // UPDATE (Edit Media)
    public function update(Request $request, $id)
    {
        $media = MediaItem::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'media_category_id' => 'required|exists:media_categories,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('media', 'public');
            $media->image = $imagePath;
        }

        $media->update([
            'title' => $request->title,
            'media_category_id' => $request->media_category_id,
        ]);

        return back()->with('success', 'Media updated successfully');
    }

    // DELETE
    public function destroy($id)
    {
        $media = MediaItem::findOrFail($id);
        $media->delete();

        return back()->with('success', 'Media deleted successfully');
    }
}