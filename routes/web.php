<?php

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');

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
        Route::post('categories/{category}/first', 'CategoryController@first')->name('categories.first');
        Route::post('categories/{category}/last', 'CategoryController@last')->name('categories.last');
        Route::post('categories/{category}/up', 'CategoryController@up')->name('categories.up');
        Route::post('categories/{category}/down', 'CategoryController@down')->name('categories.down');
        }
    );
});


