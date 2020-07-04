<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

//Route::get('/register', 'Auth\RegisterController@form')->name('register');
//Route::post('/register', 'Auth\RegisterController@register');
