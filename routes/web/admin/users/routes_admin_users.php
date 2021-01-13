<?php
//---------
// Admin.Users
//---------
Route::get('users/verify/{user}', 'UsersController@verify')->name('users.verify');
Route::resource('users', 'UsersController');
