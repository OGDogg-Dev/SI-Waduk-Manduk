<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Attraction;
use App\Models\Closure;
use App\Models\Event;
use App\Models\Facility;
use App\Models\Inquiry;
use App\Models\Merchant;
use App\Models\OperatingHour;
use App\Models\Setting;
use App\Models\TicketType;
use App\Policies\AnnouncementPolicy;
use App\Policies\AttractionPolicy;
use App\Policies\ClosurePolicy;
use App\Policies\EventPolicy;
use App\Policies\FacilityPolicy;
use App\Policies\InquiryPolicy;
use App\Policies\MerchantPolicy;
use App\Policies\OperatingHourPolicy;
use App\Policies\SettingPolicy;
use App\Policies\TicketTypePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Provider untuk registrasi policy dan gate.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Peta model ke policy.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Attraction::class => AttractionPolicy::class,
        OperatingHour::class => OperatingHourPolicy::class,
        TicketType::class => TicketTypePolicy::class,
        Event::class => EventPolicy::class,
        Announcement::class => AnnouncementPolicy::class,
        Facility::class => FacilityPolicy::class,
        Merchant::class => MerchantPolicy::class,
        Inquiry::class => InquiryPolicy::class,
        Closure::class => ClosurePolicy::class,
        Setting::class => SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Policy terdaftar otomatis oleh properti $policies.
    }
}
