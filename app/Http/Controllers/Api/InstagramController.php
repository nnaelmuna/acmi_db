<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstagramPost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InstagramController extends Controller
{
    public function index()
    {
        // Ambil data langsung dari Database lokal, di-cache selama 1 jam agar hemat resources
        $posts = Cache::remember('instagram_posts_api_v3', 60 * 60, function () {
            try {
                // Ambil 6 data terbaru berdasarkan waktu postingan dibuat
                return InstagramPost::orderBy('posted_at', 'desc')
                    ->take(6)
                    ->get()
                    ->map(function ($item) {
                        return [
                            // Return path lokal yang sudah kamu download (storage/instagram/xxx.jpg)
                            'image'    => $item->local_image_path ? 'storage/' . $item->local_image_path : null,
                            'post_url' => $item->post_url ?? '#',
                            'caption'  => $item->caption ?? '',
                            'likes'    => 0, // Set default jika di DB belum ada field likes
                            'comments' => 0, // Set default jika di DB belum ada field comments
                        ];
                    })
                    ->all();
            } catch (\Exception $e) {
                Log::error('Gagal mengambil data Instagram lokal: ' . $e->getMessage());
                return [];
            }
        });

        return response()->json([
            'success' => true,
            'data'    => $posts,
        ]);
    }
}