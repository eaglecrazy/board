<?php

namespace App\Providers;

use App\Services\Sms\FakeSender;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
