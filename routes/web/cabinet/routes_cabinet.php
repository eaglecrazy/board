<?php
use App\Http\Middleware\FilledProfile;


//---------
// Cabinet
//---------

Route::group([
    'prefix' => 'cabinet',
    'as' => 'cabinet.',
    'namespace' => 'Cabinet',
    'middleware' => ['auth'],
], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('favorites', 'FavoriteController@index')->name('favorites.index');
    Route::delete('favorites/{advert}', 'FavoriteController@remove')->name('favorites.remove');

    //---------------------------
    // Cabinet.Adverts
    //---------------------------
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
        Route::post('/{advert}/send', 'ManageController@send')->name('send');
        //закрытие
        Route::post('/{advert}/close', 'ManageController@close')->name('close');
        //удаление
        Route::delete('/{advert}/destroy', 'ManageController@destroy')->name('destroy');
    });

    //---------
    // Cabinet.Banners
    //--------
    Route::group([
        'prefix' => 'banners',
        'as' => 'banners.',
        'namespace' => 'Banners',
        'middleware' => [FilledProfile::class],
    ], function () {
        Route::get('/', 'CabinetBannerController@index')->name('index');
        Route::get('/create', 'BannerCreateController@category')->name('create');
        Route::get('/create/region/{category}/{region?}', 'BannerCreateController@region')->name('create.region');
        Route::get('/create/banner/{category}/{region?}', 'BannerCreateController@banner')->name('create.banner');
        Route::post('/create/banner/{category}/{region?}', 'BannerCreateController@store')->name('create.banner.store');
        Route::get('/show/{banner}', 'CabinetBannerController@show')->name('show');
        Route::get('/{banner}/edit', 'CabinetBannerController@editForm')->name('edit');
        Route::put('/{banner}/edit', 'CabinetBannerController@edit');
        Route::get('/{banner}/file', 'CabinetBannerController@fileForm')->name('edit_file');
        Route::put('/{banner}/file', 'CabinetBannerController@file');
        Route::post('/{banner}/send', 'CabinetBannerController@send')->name('send');
        Route::post('/{banner}/cancel', 'CabinetBannerController@cancel')->name('cancel');
        Route::post('/{banner}/order', 'CabinetBannerController@order')->name('order');
        Route::delete('/{banner}/destroy', 'CabinetBannerController@destroy')->name('destroy');
    });


    //---------
    // Cabinet.Profile
    //---------
    Route::group([
        'prefix' => 'profile',
        'as' => 'profile.',
    ], function () {
        Route::get('/', 'ProfileController@index')->name('home');
        Route::get('/edit', 'ProfileController@edit')->name('edit');
        Route::put('/update', 'ProfileController@update')->name('update');
        Route::post('/phone', 'PhoneController@sendVerifyToken');
        Route::get('/phone', 'PhoneController@form')->name('phone');
        Route::put('/phone', 'PhoneController@verify')->name('phone.verify');
        Route::post('/phone/auth', 'PhoneController@phoneAuth')->name('phone.auth');
    });

    //---------
    // Cabinet.Tickets
    //--------
    Route::resource('tickets', 'TicketController')->only(['index', 'show', 'create', 'store', 'destroy']);
    Route::post('tickets/{ticket}/message', 'TicketController@message')->name('tickets.message');

});
