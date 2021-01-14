<?php
//---------
// Cabinet.Dialogs
//--------
Route::get('dialogs', 'DialogController@allDialogs')->name('dialogs.index');
Route::get('dialogs/{advert}/message', 'DialogController@message')->name('dialogs.message');
//Route::post('tickets/{ticket}/message', 'TicketController@message')->name('tickets.message');
