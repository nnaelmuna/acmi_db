<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsoredBanner;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\TabFilterService;

class SponsoredBannerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'published');

        if ($status === 'trash') {
            $banners = SponsoredBanner::onlyTrashed()
                ->latest()
                ->paginate(9)
                ->withQueryString();
        } else {
            $banners = SponsoredBanner::where('status', $status)
                ->latest()
                ->paginate(9)
                ->withQueryString();
        }

        $tabs = TabFilterService::getTabs(SponsoredBanner::class);

        return view('sponsored-banner', compact('banners', 'tabs', 'status'));
    }

    public function store(Request $request)
    {
        $isForever = $request->has('is_forever');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
            'link_sponsored' => ['required', 'url'],
            'start_date' => ['required', 'date'],
            'end_date' => [
                $isForever ? 'nullable' : 'required',
                'date',
                function ($attribute, $value, $fail) use ($request, $isForever) {
                    if (!$isForever && $request->filled('start_date') && $value < $request->start_date) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                }
            ],
            'status' => ['required', 'in:published,draft,archived'],
        ]);

        $imagePath = $request->file('image')->store('sponsored-banners', 'public');

        SponsoredBanner::create([
            'title' => $validated['title'],
            'image' => $imagePath,
            'link_sponsored' => $validated['link_sponsored'],
            'start_date' => $validated['start_date'],
            'end_date' => $isForever ? null : $validated['end_date'],
            'is_forever' => $isForever,
            'status' => $validated['status'],
            'impressions' => 0,
        ]);

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'sponsored_banner',
            'description' => auth()->user()->name . ' added a sponsored banner',
        ]);

        return back()->with('success', 'Sponsored banner added successfully.');
    }

    public function update(Request $request, $id)
    {
        $banner = SponsoredBanner::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
            'link_sponsored' => ['required', 'url'],
            'status' => ['required', 'in:published,draft,archived'],
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $banner->image = $request->file('image')->store('sponsored-banners', 'public');
        }

        $banner->update([
            'title' => $validated['title'],
            'link_sponsored' => $validated['link_sponsored'],
            'image' => $banner->image,
            'status' => $validated['status'],
        ]);

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'sponsored_banner',
            'description' => auth()->user()->name . ' updated a sponsored banner',
        ]);

        return back()->with('success', 'Sponsored banner updated successfully.');
    }

    public function destroy($id)
    {
        $banner = SponsoredBanner::findOrFail($id);
        $banner->delete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'sponsored_banner',
            'description' => auth()->user()->name . ' moved a sponsored banner to trash',
        ]);

        return redirect()
            ->route('sponsored-banner', ['status' => 'trash'])
            ->with('success', 'Sponsored banner moved to trash successfully.');
    }

    public function restore($id)
    {
        $banner = SponsoredBanner::onlyTrashed()->findOrFail($id);
        $banner->restore();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'sponsored_banner',
            'description' => auth()->user()->name . ' restored a sponsored banner',
        ]);

        return redirect()
            ->route('sponsored-banner', ['status' => 'published'])
            ->with('success', 'Sponsored banner restored successfully.');
    }

    public function forceDelete($id)
    {
        $banner = SponsoredBanner::onlyTrashed()->findOrFail($id);

        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'sponsored_banner',
            'description' => auth()->user()->name . ' permanently deleted a sponsored banner',
        ]);

        return redirect()
            ->route('sponsored-banner', ['status' => 'trash'])
            ->with('success', 'Sponsored banner permanently deleted successfully.');
    }
}
