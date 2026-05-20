<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstagramPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApifyWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Opsional: Pasang validasi token untuk keamanan agar tidak sembarang orang bisa nembak API ini
        $token = $request->query('token');
        if ($token !== 'rahasia-acmi-2026') { // Ganti dengan token rahasiamu nanti
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Ambil data yang dikirim Apify (biasanya dalam bentuk array JSON)
        $apifyData = $request->all();

        // Kalau Apify ngirimnya dibungkus object, sesuaikan (misal: $request->input('data'))
        if (!is_array($apifyData) || empty($apifyData)) {
            return response()->json(['message' => 'No data received'], 400);
        }

        $processedCount = 0;

        foreach ($apifyData as $item) {
            $apifyImageUrl = $item['displayUrl'] ?? $item['imageUrl'] ?? null;
            $localImagePath = null;

            // 1. Eksekusi Download Gambar
            if ($apifyImageUrl) {
                try {
                    $imageResponse = Http::timeout(15)->get($apifyImageUrl);
                    
                    if ($imageResponse->successful()) {
                        $filename = 'instagram/' . Str::random(40) . '.jpg';
                        Storage::disk('public')->put($filename, $imageResponse->body());
                        $localImagePath = $filename;
                    }
                } catch (\Exception $e) {
                    Log::error('Apify Webhook - Gagal download gambar: ' . $e->getMessage());
                }
            }

            // 2. Simpan ke Database (Update jika URL sama, Create jika baru)
            if (!empty($item['url'])) {
                InstagramPost::updateOrCreate(
                    ['post_url' => $item['url']], 
                    [
                        'apify_id'         => $item['id'] ?? null,
                        'caption'          => $item['caption'] ?? null,
                        'apify_image_url'  => $apifyImageUrl,
                        'local_image_path' => $localImagePath,
                        'posted_at'        => isset($item['timestamp']) ? date('Y-m-d H:i:s', strtotime($item['timestamp'])) : now(),
                    ]
                );
                $processedCount++;
            }
        }

        Log::info("Apify Webhook memproses {$processedCount} postingan Instagram baru.");
        
        return response()->json([
            'success' => true,
            'message' => "Successfully processed {$processedCount} posts."
        ], 200);
    }
}