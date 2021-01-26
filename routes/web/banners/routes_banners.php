<?php
//---------
// Banners
//---------

Route::get('/bitem/get', 'BannerController@get')->name('banner.get');
Route::get('/bitem/{banner}/click', 'BannerController@click')->name('banner.click');
