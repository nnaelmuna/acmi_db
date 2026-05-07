<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\TabFilterService;

class MediaPartnerController extends Controller
{
    public function index(Request $request)
    {
        $partners = MediaPartner::latest()->paginate(9);
        $tabs = TabFilterService::getTabs(MediaPartner::class);

        $status = $request->get('status', 'published');

        if ($status === 'trash') {
            $partners = MediaPartner::onlyTrashed()->latest()->paginate(9);
        } else {
            $partners = MediaPartner::where('status', $status)->latest()->paginate(9);
        }

        return view('media-partner', compact('partners', 'tabs', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'link' => ['nullable', 'url'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:published,draft,archived'],
        ]);

        $imagePath = $request->file('image')->store('media-partners', 'public');

        MediaPartner::create([
            'name' => $validated['name'],
            'image' => $imagePath,
            'link' => $validated['link'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Media partner added successfully.');
    }

    public function update(Request $request, $id)
    {
        $partner = MediaPartner::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'link' => ['nullable', 'url'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:published,draft,archived'],
        ]);

        if ($request->hasFile('image')) {
            if ($partner->image && Storage::disk('public')->exists($partner->image)) {
                Storage::disk('public')->delete($partner->image);
            }

            $partner->image = $request->file('image')->store('media-partners', 'public');
        }

        $partner->update([
            'name' => $validated['name'],
            'link' => $validated['link'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'image' => $partner->image,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Media partner updated successfully.');
    }

    public function destroy($id)
    {
        $partner = MediaPartner::findOrFail($id);

        if ($partner->image && Storage::disk('public')->exists($partner->image)) {
            Storage::disk('public')->delete($partner->image);
        }

        $partner->delete();

        return back()->with('success', 'Media partner deleted successfully.');
    }
}