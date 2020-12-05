<?php

namespace App\Providers;

use App\Events\AdvertEvent;
use App\Events\CategoryDeleteEvent;
use App\Events\CategoryUpdateEvent;
use App\Events\RegionDeleteEvent;
use App\Listeners\AdvertEventListener;
use App\Listeners\CategoryDeleteEventListener;
use App\Listeners\CategoryUpdateEventListener;
use App\Listeners\RegionDeleteEventListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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

        CategoryDeleteEvent::class => [
            CategoryDeleteEventListener::class
        ],

        CategoryUpdateEvent::class => [
            CategoryUpdateEventListener::class
        ],

        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        RegionDeleteEvent::class => [
            RegionDeleteEventListener::class
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
