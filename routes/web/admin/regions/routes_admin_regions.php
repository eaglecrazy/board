<?php
//---------
// Admin.Regions
//---------
Route::resource('regions', 'RegionController');
//роут нужен для правильной генерации хлебных крошек в дочерних регионах
Route::get('regions/create/{region}', 'RegionController@create_inner')->name('regions.create-inner');
