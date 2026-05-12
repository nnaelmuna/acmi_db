<?php

namespace App\Observers;

use App\Models\Faq;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaqObserver
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

    public function created(Faq $faq): void
    {
        $this->sendWebhook('faq.updated', $faq);
    }

    /**
     * Handle the Faq "updated" event.
     */
    public function updated(Faq $faq): void
    {
        $this->sendWebhook('faq.updated', $faq);
    }

    /**
     * Handle the Faq "deleted" event.
     */
    public function deleted(Faq $faq): void
    {
        $this->sendWebhook('faq.deleted', $faq);
    }

    /**
     * Handle the Faq "restored" event.
     */
    public function restored(Faq $faq): void
    {
        $this->sendWebhook('faq.updated', $faq);
    }

    /**
     * Handle the Faq "force deleted" event.
     */
    public function forceDeleted(Faq $faq): void
    {
        $this->sendWebhook('faq.deleted', $faq);
    }
}
