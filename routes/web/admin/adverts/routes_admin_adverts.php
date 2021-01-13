<?php
Route::group([
    'prefix' => 'adverts',
    'as' => 'adverts.',
    'namespace' => 'Adverts',

], function () {
    //---------
    // Admin.Adverts.Categories
    //---------
    Route::resource('categories', 'CategoryController');
    //роут нужен для правильной генерации хлебных крошек в дочерних категориях
    Route::get('categories/create/{category}', 'CategoryController@create_inner')->name('categories.create-inner');


    Route::group([
        'prefix' => 'categories/{category}',
        'as' => 'categories.',
    ], function () {
        Route::post('/first', 'CategoryController@first')->name('first');
        Route::post('/last', 'CategoryController@last')->name('last');
        Route::post('/up', 'CategoryController@up')->name('up');
        Route::post('/down', 'CategoryController@down')->name('down');


        //---------
        // Admin.Adverts.Attributes
        //---------
        Route::resource('attributes', 'AttributeController')->except('index');
    });


    //---------
    // Admin.Adverts.Adverts
    //---------
    Route::group([
        'prefix' => 'adverts',
        'as' => 'adverts.'
    ], function () {
        Route::get('/', 'AdvertController@index')->name('index');
        Route::get('/{advert}/edit', 'AdvertController@editForm')->name('edit');
        Route::delete('/{advert}/destroy', 'AdvertController@destroy')->name('destroy');
        Route::post('{advert}/moderate', 'AdvertController@moderate')->name('moderate');
        Route::get('/{advert}/reject', 'AdvertController@rejectForm')->name('reject');
        Route::post('/{advert}/reject', 'AdvertController@reject')->name('refuse');
//*****************
        Route::put('/{advert}/edit', 'AdvertController@edit')->name('update');
    });
});
