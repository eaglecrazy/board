<?php
//---------
// Admin.Banners
//--------
Route::group([
    'prefix' => 'banners',
    'as' => 'banners.'
], function () {
    Route::get('/', 'AdminBannerController@index')->name('index');
    Route::get('/{banner}/show', 'AdminBannerController@show')->name('show');
    Route::get('/{banner}/edit', 'AdminBannerController@editForm')->name('edit');
    Route::put('/{banner}/edit', 'AdminBannerController@edit');
    Route::post('/{banner}/moderate', 'AdminBannerController@moderate')->name('moderate');
    Route::get('/{banner}/reject', 'AdminBannerController@rejectForm')->name('reject');
    Route::post('/{banner}/reject', 'AdminBannerController@reject');
    Route::post('/{banner}/pay', 'AdminBannerController@pay')->name('pay');
    Route::delete('/{banner}/destroy', 'AdminBannerController@destroy')->name('destroy');
});
