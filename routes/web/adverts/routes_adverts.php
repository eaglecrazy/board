<?php
//---------
// Adverts
//-------
Route::group([
    'prefix' => 'adverts',
    'as' => 'adverts.',
    'namespace' => 'Adverts',
], function () {
    Route::get('show/{advert}', 'AdvertController@show')->name('show');
    Route::post('show/{advert}/phone', 'AdvertController@phone')->name('phone');
    Route::post('show/{advert}/favorites', 'FavoriteController@add')->name('favorites');
    Route::delete('show/{advert}/favorites', 'FavoriteController@remove');
    Route::get('/{adverts_path?}', 'AdvertController@path')->name('index')->where('adverts_path', '.+');
});
