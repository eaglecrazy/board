<?php
//---------
// Cabinet
//---------
Route::group([
    'prefix' => 'cabinet',
    'as' => 'cabinet.',
    'namespace' => 'Cabinet',
    'middleware' => ['auth'],
], function () {
    //домашная страница кабинета - свои объявления
    Route::get('/', 'Adverts\AdvertController@index')->name('home');

    @include "routes_cabinet_favorites.php";
    @include "routes_cabinet_adverts.php";
    @include "routes_cabinet_banners.php";
    @include "routes_cabinet_dialogs.php";
    @include "routes_cabinet_profile.php";
    @include "routes_cabinet_tickets.php";
});
