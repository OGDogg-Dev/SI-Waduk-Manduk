<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\Facility;
use App\Models\Merchant;
use App\Models\TicketType;
use App\Observers\SluggableObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Daftarkan observer slug untuk model konten.
        $observer = SluggableObserver::class;

        Attraction::observe($observer);
        TicketType::observe($observer);
        Event::observe($observer);
        Announcement::observe($observer);
        Merchant::observe($observer);
        Facility::observe($observer);

        RateLimiter::for('inquiries', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
