<?php

namespace App\Observers;

use App\Models\MediaPartner;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MediaPartnerObserver
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
     * Handle the MediaPartner "created" event.
     */
    public function created(MediaPartner $mediaPartner): void
    {
        $this->sendWebhook('partner.updated');
    }

    /**
     * Handle the MediaPartner "updated" event.
     */
    public function updated(MediaPartner $mediaPartner): void
    {
        $this->sendWebhook('partner.updated');
    }

    /**
     * Handle the MediaPartner "deleted" event.
     */
    public function deleted(MediaPartner $mediaPartner): void
    {
        $this->sendWebhook('partner.deleted');
    }

    /**
     * Handle the MediaPartner "restored" event.
     */
    public function restored(MediaPartner $mediaPartner): void
    {
        $this->sendWebhook('partner.updated');
    }

    /**
     * Handle the MediaPartner "force deleted" event.
     */
    public function forceDeleted(MediaPartner $mediaPartner): void
    {
        $this->sendWebhook('partner.deleted');
    }
}
