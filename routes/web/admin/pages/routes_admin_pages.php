<?php
//---------
// Admin.Pages
//---------
Route::resource('pages', 'PageController');

Route::group([
    'prefix' => 'pages/{page}',
    'as' => 'pages.'],
    function () {
        Route::post('/first', 'PageController@first')->name('first');
        Route::post('/up', 'PageController@up')->name('up');
        Route::post('/down', 'PageController@down')->name('down');
        Route::post('/last', 'PageController@last')->name('last');
    });
