<?php

namespace App\Providers;

use App\Entity\Adverts\Category;
use App\Entity\Page;
use App\Entity\Region;
use App\Services\Banner\CostCalculator;
use App\Services\Sms\FakeSender;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CostCalculator::class, function (Application $app){
            $config = $app->make('config')->get('banner');
            return new CostCalculator($config['price']);
        });
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

}
