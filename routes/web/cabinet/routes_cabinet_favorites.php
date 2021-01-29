<?php
//---------------------------
// Cabinet.Favorites
//---------------------------
Route::get('favorites', 'FavoriteController@index')->name('favorites.index');
Route::delete('favorites/{advert}', 'FavoriteController@remove')->name('favorites.remove');
