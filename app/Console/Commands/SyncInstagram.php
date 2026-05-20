<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\InstagramPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Signature('app:sync-instagram')]
#[Description('Command description')]
class SyncInstagram extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses penjemputan data Instagram...');

        $apifyUrl = env('APIFY_INSTAGRAM_URL'); 

        // Validasi jika lupa taruh di .env
        if (!$apifyUrl) {
            $this->error('ERROR: APIFY_INSTAGRAM_URL belum disetting di file .env!');
            return;
        }

        $response = Http::timeout(30)->get($apifyUrl);
        
        if (!$response->successful()) {
            $this->error('Gagal mengambil data dari Apify!');
            return;
        }

        $items = $response->json();
        $count = 0;

        foreach ($items as $item) {
            $apifyImageUrl = $item['displayUrl'] ?? $item['imageUrl'] ?? null;
            $localImagePath = null;

            if ($apifyImageUrl) {
                try {
                    $this->info("Mendownload gambar untuk post: " . substr($item['caption'] ?? 'Tanpa Caption', 0, 30) . "...");
                    $imageResponse = Http::timeout(15)->get($apifyImageUrl);
                    
                    if ($imageResponse->successful()) {
                        $filename = 'instagram/' . Str::random(40) . '.jpg';
                        Storage::disk('public')->put($filename, $imageResponse->body());
                        $localImagePath = $filename;
                    }
                } catch (\Exception $e) {
                    $this->error('Gagal download gambar: ' . $e->getMessage());
                }
            }

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
                $count++;
            }
        }

        $this->info("Selesai! {$count} postingan Instagram berhasil di-download dan disimpan.");
    }
}
