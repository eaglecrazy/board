<?php

namespace App\Providers;

use App\Events\AdvertEvent;
use App\Events\AdvertModerationPassedEvent;
use App\Events\CategoryDeleteEvent;
use App\Events\CategoryUpdateEvent;
use App\Events\RegionDeleteEvent;
use App\Listeners\AdvertChangedListener;
use App\Listeners\AdvertEventListener;
use App\Listeners\AdvertModerationPassedListener;
use App\Listeners\CategoryDeleteEventListener;
use App\Listeners\CategoryUpdateEventListener;
use App\Listeners\RegionDeleteEventListener;
use App\Listeners\UserRegisteredEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        AdvertEvent::class => [
            AdvertEventListener::class
        ],

        AdvertModerationPassedEvent::class => [
            AdvertModerationPassedListener::class,
            AdvertChangedListener::class,
        ],

        CategoryDeleteEvent::class => [
            CategoryDeleteEventListener::class
        ],

        CategoryUpdateEvent::class => [
            CategoryUpdateEventListener::class
        ],

        Registered::class => [
            UserRegisteredEventListener::class,
        ],

        RegionDeleteEvent::class => [
            RegionDeleteEventListener::class
        ],

        SocialiteWasCalled::class => [
            // ... other providers
            'SocialiteProviders\\VKontakte\\VKontakteExtendSocialite@handle',
            'SocialiteProviders\\GitHub\\GitHubExtendSocialite@handle',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
