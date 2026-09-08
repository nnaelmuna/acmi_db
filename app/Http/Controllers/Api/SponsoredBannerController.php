<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SponsoredBanner;
use Illuminate\Support\Facades\Storage;

class SponsoredBannerController extends Controller
{
    public function index()
    {
        $banners = SponsoredBanner::where('status', 'published')
            ->whereDate('start_date', '<=', now())
            ->where(function ($query) {
                $query->where('is_forever', true)
                    ->orWhereDate('end_date', '>=', now());
            })
            ->latest()
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'image' => $banner->image ? Storage::disk('public')->url($banner->image) : null,
                    'link_sponsored' => $banner->link_sponsored,
                    'size' => $banner->size,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Data Sponsored Banner Berhasil Diambil',
            'data' => $banners,
        ], 200);
    }

    public function impression($id)
    {
        $banner = SponsoredBanner::findOrFail($id);
        $banner->increment('impressions');

        return response()->noContent();
    }

    public function click($id)
    {
        $banner = SponsoredBanner::findOrFail($id);
        $banner->increment('clicks');

        return response()->json([
            'redirect' => $banner->link_sponsored,
        ], 200);
    }
}
