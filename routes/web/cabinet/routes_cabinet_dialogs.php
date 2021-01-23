<?php
//---------
// Cabinet.Dialogs
//--------
use App\Http\Middleware\ActiveAdvert;

Route::group([
    'prefix' => 'dialogs',
    'as' => 'dialogs.',
], function () {
    Route::get('/', 'DialogController@index')->name('index');
    Route::get('{advert}/dialog', 'DialogController@dialog')->name('dialog')->middleware(ActiveAdvert::class);
//    Route::post('{dialog}/write', 'DialogController@write')->name('write')->middleware(ActiveAdvert::class);
    Route::post('{dialog}/write', 'DialogController@write')->name('write');
});
