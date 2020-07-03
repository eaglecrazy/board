<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');


//Route::get('/register', 'Auth\RegisterController@form')->name('register');
//Route::post('/register', 'Auth\RegisterController@register');
