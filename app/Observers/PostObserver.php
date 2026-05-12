<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    private function sendWebhook(string $event, Post $post): void
    {
        try {
            Http::withToken(config('services.landing.webhook_secret'))
                ->timeout(5)
                ->post(config('services.landing.webhook_url'), [
                    'event' => $event,
                    'slug'  => $post->slug,
                ]);
        } catch (\Exception $e) {
            Log::error("Webhook gagal [{$event}]: " . $e->getMessage());
        }
    }

    public function created(Post $post): void
    {
        $this->sendWebhook('article.updated', $post);
    }

    public function updated(Post $post): void
    {
        $this->sendWebhook('article.updated', $post);
    }

    public function deleted(Post $post): void
    {
        $this->sendWebhook('article.deleted', $post);
    }

    public function restored(Post $post): void
    {
        $this->sendWebhook('article.updated', $post);
    }

    public function forceDeleted(Post $post): void
    {
        $this->sendWebhook('article.deleted', $post);
    }
}