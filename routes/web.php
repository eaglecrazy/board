<?php

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth'],
], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('users/verify/{user}', 'UsersController@verify')->name('users.verify');
    Route::resource('users', 'UsersController');
});
