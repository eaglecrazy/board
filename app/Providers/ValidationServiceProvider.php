<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('files_count', function($attribute, $value, $parameters, $validator) {
            return count($value) <= $parameters[0];
        });

        Validator::replacer('files_count', function($message, $attribute, $rule, $parameters) {
            return 'Можно загрузить не более ' . $parameters[0] . ' фотографий.';
        });
    }

    public function register()
    {
        //
    }
}
