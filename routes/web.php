<?php

use App\Http\Middleware\FilledProfile;

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
    Route::get('/', 'HomeController@index')->name('home');
    //---------
    // Users
    //---------
    Route::get('users/verify/{user}', 'UsersController@verify')->name('users.verify');
    Route::resource('users', 'UsersController');

    //---------
    // Regions
    //---------
    Route::resource('regions', 'RegionController');
    //роут нужен для правильной генерации хлебных крошек в дочерних регионах
    Route::get('regions/create/{region}', 'RegionController@create_inner')->name('regions.create-inner');

    //---------
    //Adverts
    //---------
    Route::group([
        'prefix' => 'adverts',
        'as' => 'adverts.',
        'namespace' => 'Adverts',

    ], function () {
        //---------
        //Categories
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
            //Attributes
            //---------
            Route::resource('attributes', 'AttributeController')->except('index');
        });
        //---------
        //Adverts
        //---------
        Route::group([
            'prefix' => 'adverts',
            'as' => 'adverts.'
        ], function () {
            Route::get('/{advert}/edit', 'ManageController@editForm')->name('edit');
            Route::get('/{advert}/photos', 'ManageController@photosForm')->name('photos');
            Route::delete('/{advert}/destroy', 'ManageController@destroy')->name('destroy');
            Route::post('{advert}/moderate', 'ManageController@moderate')->name('moderate');
            Route::get('/{advert}/reject', 'ManageController@rejectForm')->name('reject');
        });

    }
    );
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
    //этот роут нужно будет поменять
    Route::get('show/{advert}', 'AdvertController@show')->name('show');
    Route::post('show/{advert}/phone', 'AdvertController@phone')->name('phone');
    Route::post('show/{advert}/phone', 'AdvertController@phone')->name('phone');
    Route::get('all/{category?}', 'AdvertController@index')->name('index.all');
    Route::get('{region?}/{category?}', 'AdvertController@index')->name('index');


//    Route::post('show/{advert}/favorites', 'FavoriteController@add')->name('favorites');
//    Route::delete('show/{advert}/favorites', 'FavoriteController@remove');

//    Route::get('{adverts_path?}', 'AdvertController@index')->name('index')->where('adverts_path', '.+');
});



//---------
// Auth
//---------
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');
Route::get('/login/phone', 'Auth\LoginController@phone')->name('login.phone');
Route::post('/login/phone', 'Auth\LoginController@verify');




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

    //---------
    // Cabinet Profile
    //---------
    Route::group([
        'prefix' => 'profile',
        'as' => 'profile.',
    ], function () {
        Route::get('/', 'ProfileController@index')->name('home');
        Route::get('/edit', 'ProfileController@edit')->name('edit');
        Route::put('/update', 'ProfileController@update')->name('update');
        Route::post('/phone', 'PhoneController@request');
        Route::get('/phone', 'PhoneController@form')->name('phone');
        Route::put('/phone', 'PhoneController@verify')->name('phone.verify');
        Route::post('/phone/auth', 'PhoneController@auth')->name('phone.auth');
    });
    //---------
    // Cabinet Adverts
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
        Route::get('/{advert}/photos', 'ManageController@photosForm')->name('photos');
        //редактирование
        Route::get('/{advert}/edit', 'ManageController@editForm')->name('edit');
        Route::put('/{advert}/edit', 'ManageController@update')->name('update');
        //отправка на модерацию
        Route::post('/{advert}/send', 'ManageController@send')->name('send');
        //удаление
        Route::delete('/{advert}/destroy', 'ManageController@destroy')->name('destroy');
    });

});

