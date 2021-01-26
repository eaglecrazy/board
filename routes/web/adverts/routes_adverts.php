<?php
//---------
// Adverts
//-------
Route::group([
    'prefix' => 'items',
    'as' => 'adverts.',
    'namespace' => 'Adverts',
], function () {
    Route::get('show/{advert}', 'AdvertController@show')->name('show');
    Route::post('show/{advert}/phone', 'AdvertController@phone')->name('phone');
    Route::post('show/{advert}/favorites', 'FavoriteController@add')->name('favorites');
    Route::delete('show/{advert}/favorites', 'FavoriteController@remove');

    //этот роут должен идти последним в группе
    Route::get('/{adverts_path?}', 'AdvertController@path')->name('index')->where('adverts_path', '.+');
});
