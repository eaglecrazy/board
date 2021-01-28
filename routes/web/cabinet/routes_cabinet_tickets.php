<?php
//---------
// Cabinet.Tickets
//--------
Route::resource('tickets', 'TicketController')->only(['index', 'show', 'create', 'store', 'destroy']);
Route::post('tickets/{ticket}/message', 'TicketController@message')->name('tickets.message');
