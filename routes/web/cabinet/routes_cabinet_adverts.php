<?php
//---------------------------
// Cabinet.Adverts
//---------------------------
use App\Http\Middleware\ActiveAdvert;
use App\Http\Middleware\FilledProfile;

Route::group([
    'prefix' => 'adverts',
    'as' => 'adverts.',
    'namespace' => 'Adverts',
    'middleware' => [FilledProfile::class],
], function () {
    //создание объявления
    Route::get('/', 'AdvertController@index')->name('index');
    Route::get('/create/category', 'CreateController@category')->name('create.category');
    Route::get('/create/region/{category}/{region?}', 'CreateController@region')->name('create.region');
    Route::get('/create/advert/{category}/{region?}', 'CreateController@advert')->name('create.advert');
    Route::post('/create/advert/{category}/{region?}', 'CreateController@store')->name('create.advert.store');
    //редактирование
    Route::get('/{advert}/edit', 'ManageController@editForm')->name('edit');
    Route::put('/{advert}/advert-update', 'ManageController@updateAdvert')->name('update.advert');
    Route::put('/{advert}/attributes-update', 'ManageController@updateAttrubutes')->name('update.attrubutes');
    Route::post('/{advert}/photos-add', 'ManageController@addPhotos')->name('add.photos');
    Route::delete('/{advert}/{photo}/destroy', 'ManageController@destroyPhoto')->name('destroy.photo');

    //отправка на модерацию
    Route::post('/{advert}/send', 'ManageController@sendToModeration')->name('sendToModeration');
    //закрытие
    Route::post('/{advert}/close', 'ManageController@close')->name('close');
    //удаление
    Route::delete('/{advert}/destroy', 'ManageController@destroy')->name('destroy');
});
