<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Models\MediaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\TabFilterService;

class MediaItemController extends Controller
{
    public function index(Request $request)
    {
        $categories = MediaCategory::orderBy('is_default', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        $allMedia = MediaItem::with('category')->get();

        $counts = $categories->mapWithKeys(function ($cat) use ($allMedia) {
            return [$cat->slug => $allMedia->where('media_category_id', $cat->id)->count()];
        })->toArray();

        $query = MediaItem::with('category');

        $status = $request->get('status', 'published');

        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            $query->where('status', $status);
        }

        if ($request->filled('category')) {
            $query->where('media_category_id', $request->category);
        }

        $media = $query->latest()->paginate(9)->withQueryString();

        $tabs = TabFilterService::getTabs(MediaItem::class);

        return view('media', compact('media', 'categories', 'allMedia', 'counts', 'tabs', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'media_category_id' => 'required|exists:media_categories,id',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'status' => 'required|in:published,draft,archived',
        ]);

        $imagePath = $request->file('image')->store('media', 'public');

        MediaItem::create([
            'title' => $request->title,
            'media_category_id' => $request->media_category_id,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('media', ['status' => $request->status])
            ->with('success', 'Media added successfully');
    }

    public function update(Request $request, $id)
    {
        $media = MediaItem::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'media_category_id' => 'required|exists:media_categories,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'status' => 'required|in:published,draft,archived',
        ]);

        if ($request->hasFile('image')) {
            if ($media->image && Storage::disk('public')->exists($media->image)) {
                Storage::disk('public')->delete($media->image);
            }

            $media->image = $request->file('image')->store('media', 'public');
        }

        $media->update([
            'title' => $request->title,
            'media_category_id' => $request->media_category_id,
            'image' => $media->image,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('media', ['status' => $request->status])
            ->with('success', 'Media updated successfully');
    }

    public function destroy($id)
    {
        $media = MediaItem::findOrFail($id);
        $media->delete();

        return redirect()
            ->route('media', ['status' => 'trash'])
            ->with('success', 'Media moved to trash successfully');
    }

    public function restore($id)
    {
        $media = MediaItem::onlyTrashed()->findOrFail($id);
        $media->restore();

        return redirect()
            ->route('media', ['status' => $media->status ?? 'published'])
            ->with('success', 'Media restored successfully');
    }

    public function forceDelete($id)
    {
        $media = MediaItem::onlyTrashed()->findOrFail($id);

        if ($media->image && Storage::disk('public')->exists($media->image)) {
            Storage::disk('public')->delete($media->image);
        }

        $media->forceDelete();

        return redirect()
            ->route('media', ['status' => 'trash'])
            ->with('success', 'Media permanently deleted successfully');
    }
}
