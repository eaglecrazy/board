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
        Route::get('/', 'AdminAdvertController@index')->name('index');
        Route::post('{advert}/moderate', 'AdminAdvertController@moderate')->name('moderate');
        Route::get('/{advert}/reject', 'AdminAdvertController@rejectForm')->name('reject');
        Route::post('/{advert}/reject', 'AdminAdvertController@reject')->name('refuse');
        Route::delete('/{advert}/destroy', 'AdminAdvertController@destroy')->name('destroy');
        //редактирование
        Route::get('/{advert}/edit', 'AdminAdvertController@editForm')->name('edit');
        Route::put('/{advert}/advert-update', 'AdminAdvertController@updateAdvert')->name('update.advert');
        Route::put('/{advert}/attributes-update', 'AdminAdvertController@updateAttrubutes')->name('update.attributes');
        Route::post('/{advert}/photos-add', 'AdminAdvertController@addPhotos')->name('add.photos');
        Route::delete('/{advert}/{photo}/destroy', 'AdminAdvertController@destroyPhoto')->name('destroy.photo');
    });
});
