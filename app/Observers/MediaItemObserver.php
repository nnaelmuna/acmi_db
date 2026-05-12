<?php

namespace App\Observers;

use App\Models\MediaItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MediaItemObserver
{
    private function sendWebhook(string $event): void
    {
        try {
            Http::withToken(config('services.landing.webhook_secret'))
                ->timeout(5)
                ->post(config('services.landing.webhook_url'), [
                    'event' => $event,
                ]);
        } catch (\Exception $e) {
            Log::error("Webhook gagal [{$event}]: " . $e->getMessage());
        }
    }

    /**
     * Handle the MediaItem "created" event.
     */
    public function created(MediaItem $mediaItem): void
    {
        $this->sendWebhook('gallery.updated', $mediaItem);
    }

    /**
     * Handle the MediaItem "updated" event.
     */
    public function updated(MediaItem $mediaItem): void
    {
        $this->sendWebhook('gallery.updated', $mediaItem);
    }

    /**
     * Handle the MediaItem "deleted" event.
     */
    public function deleted(MediaItem $mediaItem): void
    {
        $this->sendWebhook('gallery.deleted', $mediaItem);
    }

    /**
     * Handle the MediaItem "restored" event.
     */
    public function restored(MediaItem $mediaItem): void
    {
        $this->sendWebhook('gallery.updated', $mediaItem);
    }

    /**
     * Handle the MediaItem "force deleted" event.
     */
    public function forceDeleted(MediaItem $mediaItem): void
    {
        $this->sendWebhook('gallery.deleted', $mediaItem);
    }
}
