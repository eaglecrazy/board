<?php

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

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
    // Profile
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
    });


});

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
    }
    );
});


