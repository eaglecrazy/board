<?php
//---------
// Cabinet.Banners
//--------
use App\Http\Middleware\FilledProfile;

Route::group([
    'prefix' => 'banners',
    'as' => 'banners.',
    'namespace' => 'Banners',
    'middleware' => [FilledProfile::class],
], function () {

//отображение
    Route::get('/', 'CabinetBannerController@index')->name('index');
    Route::get('/show/{banner}', 'CabinetBannerController@show')->name('show');
// создание
    Route::get('/create', 'BannerCreateController@category')->name('create');
    Route::get('/create/region/{category}/{region?}', 'BannerCreateController@region')->name('create.region');
    Route::get('/create/banner/{category}/{region?}', 'BannerCreateController@banner')->name('create.banner');
    Route::post('/create/banner/{category}/{region?}', 'BannerCreateController@store')->name('create.banner.store');
// редактирование
    Route::get('/{banner}/edit', 'CabinetBannerController@editForm')->name('edit');
    Route::put('/{banner}/edit', 'CabinetBannerController@edit');
    Route::get('/{banner}/file', 'CabinetBannerController@fileForm')->name('edit_file');
    Route::put('/{banner}/file', 'CabinetBannerController@file');
// статусы
    Route::post('/{banner}/send', 'CabinetBannerController@sendToModeration')->name('sendToModeration');
    Route::post('/{banner}/cancel', 'CabinetBannerController@cancelModeration')->name('cancelModeration');
    Route::post('/{banner}/order', 'CabinetBannerController@order')->name('order');
    Route::delete('/{banner}/destroy', 'CabinetBannerController@destroy')->name('destroy');
});
