<?php
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
