<?php


//---------
// Auth
//---------
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');
Route::get('/login/phone', 'Auth\LoginController@phone')->name('login.phone');
Route::post('/login/phone', 'Auth\LoginController@verify');

Route::get('/login/{network}', 'Auth\SocialNetworkController@redirect')->name('login.social-network');
Route::get('/login/{network}/callback', 'Auth\SocialNetworkController@callback');

//почему то этого роута не было в вендоре
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

