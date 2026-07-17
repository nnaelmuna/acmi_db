<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestimonialObserver
{
    private function notifyFrontend(string $event, Testimonial $testimonial)
    {
        try {
            Http::withToken(config('services.landing.webhook_secret'))
                ->timeout(5)
                ->post(config('services.landing.webhook_url'), [
                    'event' => "testimonial.{$event}",
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
