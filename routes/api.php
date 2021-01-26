<?php

use Illuminate\Http\Request;

Route::group([
    'as' => 'api.',
    'namespace' => 'Api'
],
    function () {
//        Route::get('/', 'HomeController@home');
        Route::post('register', 'Auth\RegisterController@register');

        Route::middleware('auth:api')->group(function () {
            Route::get('adverts/{advert}', 'Adverts\AdvertController@showAdvert');
            Route::get('adverts', 'Adverts\AdvertController@advertsList');
            Route::post('/adverts/{advert}/favorite', 'Adverts\FavoriteController@add');
            Route::delete('/adverts/{advert}/favorite', 'Adverts\FavoriteController@remove');
            Route::group(
                [
                    'prefix' => 'user',
                    'as' => 'user.',
                    'namespace' => 'User',
                ],
                function () {
                    Route::get('/', 'ProfileController@show');
                    Route::put('/', 'ProfileController@update');
                    Route::get('/favorites', 'FavoriteController@index');
                    Route::delete('/favorites/{advert}', 'FavoriteController@remove');

                    Route::resource('adverts', 'AdvertController')->only('index', 'show', 'update', 'destroy');
                    Route::post('/adverts/create/{category}/{region?}', 'AdvertController@store');

                    //todo нужно сделать работу с фоточками
//                    Route::put('/adverts/{advert}/photos', 'AdvertController@photos');
                    Route::put('/adverts/{advert}/attributes', 'AdvertController@updateAttributes');
                    Route::post('/adverts/{advert}/send', 'AdvertController@sendToModeration');
                    Route::post('/adverts/{advert}/close', 'AdvertController@close');
                    Route::delete('/adverts/{advert}', 'AdvertController@destroy');
                }
            );
        });
    });
