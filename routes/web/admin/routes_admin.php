<?php
//---------
// Admin
//---------
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth', 'can:admin-panel'],
], function () {

    Route::get('/', 'HomeController@index')->name('home');
    //загрузка картинки для "статической страницы"
    Route::post('/ajax/upload/image', 'UploadController@image')->name('ajax.upload.image');

    require_once('adverts/routes_admin_adverts.php');
    require_once('banners/routes_admin_banners.php');
    require_once('pages/routes_admin_pages.php');
    require_once('regions/routes_admin_regions.php');
    require_once('tickets/routes_admin_tickets.php');
    require_once('users/routes_admin_users.php');
});
