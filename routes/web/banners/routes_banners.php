<?php
//---------
// Banners
//---------

Route::get('/bitem/get', 'BannerController@get')->name('banner.get');
Route::get('/bitem/{banner}/go', 'BannerController@click')->name('banner.click');
