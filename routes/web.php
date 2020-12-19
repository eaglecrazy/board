<?php

use App\Http\Middleware\FilledProfile;

//---------
// Banners
//---------

Route::get('/banner/get', 'BannerController@get')->name('banner.get');
Route::get('/banner/{banner}/click', 'BannerController@click')->name('banner.click');

//---------
// Home
//---------

Route::get('/', 'HomeController@index')->name('home');




//---------
// Admin
//---------
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth', 'can:admin-panel'],
], function () {

    //---------
    // Admin.Home
    //---------
    Route::get('/', 'HomeController@index')->name('home');

    //---------
    // Admin.Adverts
    //---------
    Route::group([
        'prefix' => 'adverts',
        'as' => 'adverts.',
        'namespace' => 'Adverts',

    ], function () {
        //---------
        // Admin.Adverts.Categories
        //---------
        Route::resource('categories', 'CategoryController');
        //роут нужен для правильной генерации хлебных крошек в дочерних категориях
        Route::get('categories/create/{category}', 'CategoryController@create_inner')->name('categories.create-inner');
        Route::group([
            'prefix' => 'categories/{category}',
            'as' => 'categories.',
        ], function () {
            Route::post('/first', 'CategoryController@first')->name('first');
            Route::post('/last', 'CategoryController@last')->name('last');
            Route::post('/up', 'CategoryController@up')->name('up');
            Route::post('/down', 'CategoryController@down')->name('down');
            //---------
            // Admin.Adverts.Attributes
            //---------
            Route::resource('attributes', 'AttributeController')->except('index');
        });
        //---------
        // Admin.Adverts.Adverts
        //---------
        Route::group([
            'prefix' => 'adverts',
            'as' => 'adverts.'
        ], function () {
            Route::get('/', 'AdvertController@index')->name('index');
            Route::get('/{advert}/edit', 'AdvertController@editForm')->name('edit');
            Route::get('/{advert}/photos', 'AdvertController@photosForm')->name('photos');
            Route::delete('/{advert}/destroy', 'AdvertController@destroy')->name('destroy');
            Route::post('{advert}/moderate', 'AdvertController@moderate')->name('moderate');
            Route::get('/{advert}/reject', 'AdvertController@rejectForm')->name('reject');
            Route::post('/{advert}/reject', 'AdvertController@reject')->name('refuse');
            Route::put('/{advert}/edit', 'AdvertController@edit')->name('update');
        });
    });

    //---------
    // Admin.Banners
    //--------
    Route::group([
        'prefix' => 'banners',
        'as' => 'banners.'
    ], function () {
        Route::get('/', 'AdminBannerController@index')->name('index');
        Route::get('/{banner}/show', 'AdminBannerController@show')->name('show');
        Route::get('/{banner}/edit', 'AdminBannerController@editForm')->name('edit');
        Route::put('/{banner}/edit', 'AdminBannerController@edit');
        Route::post('/{banner}/moderate', 'AdminBannerController@moderate')->name('moderate');
        Route::get('/{banner}/reject', 'AdminBannerController@rejectForm')->name('reject');
        Route::post('/{banner}/reject', 'AdminBannerController@reject');
        Route::post('/{banner}/pay', 'AdminBannerController@pay')->name('pay');
        Route::delete('/{banner}/destroy', 'AdminBannerController@destroy')->name('destroy');
    });


    //---------
    // Admin.Users
    //---------
    Route::get('users/verify/{user}', 'UsersController@verify')->name('users.verify');
    Route::resource('users', 'UsersController');

    //---------
    // Admin.Regions
    //---------
    Route::resource('regions', 'RegionController');
    //роут нужен для правильной генерации хлебных крошек в дочерних регионах
    Route::get('regions/create/{region}', 'RegionController@create_inner')->name('regions.create-inner');


});


//---------
// Ajax
//---------
//Route::get('/ajax/regions', 'Ajax\RegionController@get')->name('ajax.regions');


//---------
// Adverts
//-------
Route::group([
    'prefix' => 'adverts',
    'as' => 'adverts.',
    'namespace' => 'Adverts',
], function () {
    Route::get('show/{advert}', 'AdvertController@show')->name('show');
    Route::post('show/{advert}/phone', 'AdvertController@phone')->name('phone');
    Route::post('show/{advert}/favorites', 'FavoriteController@add')->name('favorites');
    Route::delete('show/{advert}/favorites', 'FavoriteController@remove');
    Route::get('/{adverts_path?}', 'AdvertController@path')->name('index')->where('adverts_path', '.+');
});


//---------
// Auth
//---------
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');
Route::get('/login/phone', 'Auth\LoginController@phone')->name('login.phone');
Route::post('/login/phone', 'Auth\LoginController@verify');

Route::get('/login/{network}', 'Auth\SocialNetworkController@redirect')->name('login.social-network');
Route::get('/login/{network}/callback', 'Auth\SocialNetworkController@callback');

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

    //---------
    // Cabinet.Adverts
    //---------
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
        //добавление фото
        Route::post('/{advert}/photos', 'ManageController@photos');
        Route::get('/{advert}/photos', 'ManageController@editPhotosForm')->name('photos');
        //редактирование
        Route::get('/{advert}/edit', 'ManageController@editForm')->name('edit');
        Route::put('/{advert}/edit', 'ManageController@edit')->name('update');
        //отправка на модерацию
        Route::post('/{advert}/send', 'ManageController@send')->name('send');
        //закрытие
        Route::post('/{advert}/close', 'ManageController@close')->name('close');
        //удаление
        Route::delete('/{advert}/destroy', 'ManageController@destroy')->name('destroy');
        //
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

});

