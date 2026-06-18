<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestimonialObserver
{
    private function notifyFrontend(string $event, Testimonial $testimonial)
    {
        $frontendUrl = env('FRONTEND_WEBHOOK_URL', 'http://localhost:8000/api/webhook');
        
        try {
            Http::post($frontendUrl, [
                'event' => "testimonial.{$event}",
                'data' => $testimonial->toArray(),
                'timestamp' => now()->toIso8601String()
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to notify frontend about testimonial {$event}: " . $e->getMessage());
        }
    }

    public function created(Testimonial $testimonial)
    {
        $this->notifyFrontend('created', $testimonial);
    }

    public function updated(Testimonial $testimonial)
    {
        $this->notifyFrontend('updated', $testimonial);
    }

    public function deleted(Testimonial $testimonial)
    {
        $this->notifyFrontend('deleted', $testimonial);
    }

    public function restored(Testimonial $testimonial)
    {
        $this->notifyFrontend('restored', $testimonial);
    }

    public function forceDeleted(Testimonial $testimonial)
    {
        $this->notifyFrontend('forceDeleted', $testimonial);
    }
}
