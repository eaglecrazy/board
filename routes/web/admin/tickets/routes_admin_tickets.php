<?php
//---------
// Admin.Tickets
//---------
Route::group(['prefix' => 'tickets', 'as' => 'tickets.'], function () {
    Route::get('/', 'TicketController@index')->name('index');
    Route::get('/{ticket}/show', 'TicketController@show')->name('show');
    Route::get('/{ticket}/edit', 'TicketController@editForm')->name('edit');
    Route::put('/{ticket}/edit', 'TicketController@edit');
    Route::post('{ticket}/message', 'TicketController@message')->name('message');
    Route::post('/{ticket}/close', 'TicketController@close')->name('close');
    Route::post('/{ticket}/approve', 'TicketController@approve')->name('approve');
    Route::post('/{ticket}/reopen', 'TicketController@reopen')->name('reopen');
    Route::delete('/{ticket}/destroy', 'TicketController@destroy')->name('destroy');
});
