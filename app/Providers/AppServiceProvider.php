<?php

namespace App\Providers;

use App\Models\Faq;
use App\Models\MediaItem;
use App\Models\MediaPartner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Testimonial;
use App\Observers\FaqObserver;
use App\Observers\MediaItemObserver;
use App\Observers\MediaPartnerObserver;
use App\Observers\PostObserver;
use App\Observers\ProductObserver;
use App\Observers\TestimonialObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config("app.env") !== "local" || str_starts_with(config("app.url"), "https://")) {
            \Illuminate\Support\Facades\URL::forceScheme("https");
        }

        Post::observe(PostObserver::class);
        Faq::observe(FaqObserver::class);
        Product::observe(ProductObserver::class);
        MediaItem::observe(MediaItemObserver::class);
        MediaPartner::observe(MediaPartnerObserver::class);
        Testimonial::observe(TestimonialObserver::class);
    }
}
