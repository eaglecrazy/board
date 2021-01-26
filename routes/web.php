<?php

Route::get('/', 'HomeController@index')->name('home');

require_once('web/admin/routes_admin.php');
require_once('web/auth/routes_auth.php');
require_once('web/adverts/routes_adverts.php');
require_once('web/banners/routes_banners.php');
require_once('web/cabinet/routes_cabinet.php');

Route::get('/horizon', function(){
    return redirect(URL::to('/') . '/horizon/dashboard');
})->name('horizon');

Route::get('/', 'HomeController@index')->name('home');

//этот роут должен быть последним
Route::get('/{page_path}', 'PageController@show')->name('page')->where('page_path', '.+');
