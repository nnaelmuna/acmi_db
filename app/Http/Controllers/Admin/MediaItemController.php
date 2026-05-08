<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Models\MediaCategory;
use Illuminate\Http\Request;
use App\Services\TabFilterService;

class MediaItemController extends Controller
{
    // LIST MEDIA
    public function index(Request $request)
    {
        // 1. Siapkan data kategori untuk dropdown
        $categories = MediaCategory::orderBy('is_default', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        // 2. (Opsional) Hitung jumlah media per kategori
        $allMedia = MediaItem::with('category')->get();
        $counts = $categories->mapWithKeys(function ($cat) use ($allMedia) {
            return [$cat->slug => $allMedia->where('media_category_id', $cat->id)->count()];
        })->toArray();

        // 3. MULAI QUERY BUILDER (JANGAN DI-GET DULU!)
        $query = MediaItem::with('category');

        // A. Filter Status & Trash
        $status = $request->get('status', 'published');
        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            // (Pastikan tabel media_items kamu punya kolom 'status' ya)
            $query->where('status', $status);
        }

        // B. Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                // Ubah jadi 'name' karena di Blade kamu ngirim ->name
                $q->where('name', $request->category);
            });
        }

        // C. Eksekusi Query Gabungan
        $media = $query->latest()->get();
        // ==========================================

        // 4. Siapkan komponen Tab
        $tabs = TabFilterService::getTabs(MediaItem::class);

        return view('media', compact('media', 'categories', 'allMedia', 'counts', 'tabs', 'status'));
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
