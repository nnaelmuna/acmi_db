<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductObserver
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

    public function created(Product $product): void
    {
        $this->sendWebhook('product.updated', $product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->sendWebhook('product.updated', $product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->sendWebhook('product.deleted', $product);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->sendWebhook('product.updated', $product);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->sendWebhook('product.deleted', $product);
    }
}
