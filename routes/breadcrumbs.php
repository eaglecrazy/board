<?php

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Page;
use App\Entity\Region;
use App\Entity\Ticket\Ticket;
use App\Entity\User\User;
use App\Http\Router\AdvertsPath;
use App\Http\Router\PagePath;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;


//-------------------------------------------------------------------------
//Home + pages
//-------------------------------------------------------------------------

//home
Breadcrumbs::register('home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Главная', route('home'));
});

Breadcrumbs::register('page', function (BreadcrumbsGenerator $crumbs, PagePath $path) {
    if ($parent = $path->page->parent) {
        $crumbs->parent('page', $path->withPage($path->page->parent));
    } else {
        $crumbs->parent('home');
    }
    $crumbs->push($path->page->title, route('page', $path));
});

//-------------------------------------------------------------------------
//Аутентификация, регистрация, ЛК
//-------------------------------------------------------------------------

//login
Breadcrumbs::register('login', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Вход', route('login'));
});

//login.phone
Breadcrumbs::register('login.phone', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Двухфакторная аутентификация', route('login.phone'));
});

//register
Breadcrumbs::register('register', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Регистрация', route('register'));
});

//password.request
Breadcrumbs::register('password.request', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('login');
    $crumbs->push('Восстановление пароля', route('password.request'));
});

//password.reset
Breadcrumbs::register('password.reset', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('password.request');
    $crumbs->push('Сброс пароля');
});


//-------------------------------------------------------------------------
//Admin
//-------------------------------------------------------------------------

//admin.home
Breadcrumbs::register('admin.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Админка', route('admin.home'));
});


//-------------------------------------------------------------------------
// Admin.Adverts.AdvertController
//-------------------------------------------------------------------------
Breadcrumbs::register('admin.adverts.adverts.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Объявления', route('admin.adverts.adverts.index'));
});

//-------------------------------------------------------------------------
// Admin.Adverts.AttributeController
//-------------------------------------------------------------------------

//admin.adverts.categories.attributes.create
Breadcrumbs::register('admin.adverts.categories.attributes.create', function (BreadcrumbsGenerator $crumbs, Category $category) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Создание атрибута');
});

//admin.adverts.categories.attributes.show
Breadcrumbs::register('admin.adverts.categories.attributes.show', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Атрибут: ' . $attribute->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
});

//admin.adverts.categories.attributes.edit
Breadcrumbs::register('admin.adverts.categories.attributes.edit', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute) {
    $crumbs->parent('admin.adverts.categories.attributes.show', $category, $attribute);
    $crumbs->push('Редактировать атрибут');
});


//-------------------------------------------------------------------------
// Admin.Adverts.ManageController
//-------------------------------------------------------------------------

//admin.adverts.adverts.edit
Breadcrumbs::register('admin.adverts.adverts.edit', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Редактирование: ' . $advert->title, route('cabinet.adverts.edit', $advert));
});

//admin.adverts.adverts.photos
Breadcrumbs::register('admin.adverts.adverts.photos', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Добавление фотографий: ' . $advert->title, route('cabinet.adverts.photos', $advert));
});

//admin.adverts.adverts.reject
Breadcrumbs::register('admin.adverts.adverts.reject', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.show', $advert);
    $crumbs->push('Причина отклонения', route('admin.adverts.adverts.reject', $advert));
});


//-------------------------------------------------------------------------
// Admin.AdminBannerController
//-------------------------------------------------------------------------

//admin.banners.edit
Breadcrumbs::register('admin.banners.edit', function (BreadcrumbsGenerator $crumbs, Banner $banner) {
    $crumbs->parent('admin.banners.show', $banner);
    $crumbs->push('Редактирование', route('admin.banners.edit', $banner));
});

//admin.banners.index
Breadcrumbs::register('admin.banners.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Баннеры', route('admin.banners.index'));
});

//admin.banners.reject
Breadcrumbs::register('admin.banners.reject', function (BreadcrumbsGenerator $crumbs, Banner $banner) {
    $crumbs->parent('admin.banners.show', $banner);
    $crumbs->push('Отклонить', route('admin.banners.reject', $banner));
});

//admin.banners.show
Breadcrumbs::register('admin.banners.show', function (BreadcrumbsGenerator $crumbs, Banner $banner) {
    $crumbs->parent('admin.banners.index');
    $crumbs->push($banner->name, route('admin.banners.show', $banner));
});

//-------------------------------------------------------------------------
// Admin.CategoriesController
//-------------------------------------------------------------------------

//admin.adverts.categories.index
Breadcrumbs::register('admin.adverts.categories.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Категории', route('admin.adverts.categories.index'));
});

//admin.adverts.categories.index
Breadcrumbs::register('admin.adverts.categories.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.adverts.categories.index');
    $crumbs->push('Создать категорию', route('admin.adverts.categories.create'));
});

//admin.adverts.categories.create-inner
Breadcrumbs::register('admin.adverts.categories.create-inner', function (BreadcrumbsGenerator $crumbs, Category $category) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Создать дочернюю категорию');
});

//admin.adverts.categories.show
Breadcrumbs::register('admin.adverts.categories.show', function (BreadcrumbsGenerator $crumbs, Category $category) {
    if ($parent = $category->parent)
        $crumbs->parent('admin.adverts.categories.show', $parent);
    else
        $crumbs->parent('admin.adverts.categories.index');
    $crumbs->push($category->name, route('admin.adverts.categories.show', $category));
});

//admin.adverts.categories.edit
Breadcrumbs::register('admin.adverts.categories.edit', function (BreadcrumbsGenerator $crumbs, Category $category) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Редактировать категорию', route('admin.adverts.categories.edit', $category));
});


//-------------------------------------------------------------------------
// Admin.PagesController
//-------------------------------------------------------------------------

//admin.pages.create
Breadcrumbs::register('admin.pages.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.pages.index');
    $crumbs->push('Создание страницы', route('admin.pages.create'));
});

//admin.pages.edit
Breadcrumbs::register('admin.pages.edit', function (BreadcrumbsGenerator $crumbs, Page $page) {
    $crumbs->parent('admin.pages.show', $page);
    $crumbs->push('Редактирование', route('admin.pages.edit', $page));
});

//admin.pages.index
Breadcrumbs::register('admin.pages.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Страницы', route('admin.pages.index'));
});

//admin.pages.show
Breadcrumbs::register('admin.pages.show', function (BreadcrumbsGenerator $crumbs, Page $page) {
    if ($parent = $page->parent) {
        $crumbs->parent('admin.pages.show', $parent);
    } else {
        $crumbs->parent('admin.pages.index');
    }
    $crumbs->push($page->title, route('admin.pages.show', $page));
});

//-------------------------------------------------------------------------
// Admin.RegionsController
//-------------------------------------------------------------------------

//admin.regions.index
Breadcrumbs::register('admin.regions.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Регионы', route('admin.regions.index'));
});

//admin.regions.create
Breadcrumbs::register('admin.regions.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.regions.index');
    $crumbs->push('Создание корневого региона', route('admin.regions.create'));
});

//admin.regions.create-inner
Breadcrumbs::register('admin.regions.create-inner', function (BreadcrumbsGenerator $crumbs, Region $region) {
    $crumbs->parent('admin.regions.show', $region);
    $crumbs->push('Создание внутреннего региона', route('admin.regions.create'));
});

//admin.regions.show
Breadcrumbs::register('admin.regions.show', function (BreadcrumbsGenerator $crumbs, Region $region) {
    if ($parent = $region->parent)
        $crumbs->parent('admin.regions.show', $parent);
    else
        $crumbs->parent('admin.regions.index');
    $crumbs->push($region->name, route('admin.regions.show', $region));
});

//admin.regions.edit
Breadcrumbs::register('admin.regions.edit', function (BreadcrumbsGenerator $crumbs, Region $region) {
    $crumbs->parent('admin.regions.show', $region);
    $crumbs->push('Редактировать регион', route('admin.regions.edit', $region));
});

//-------------------------------------------------------------------------
// Admin.Tickets.Controller
//-------------------------------------------------------------------------

Breadcrumbs::register('admin.tickets.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Заявки', route('admin.tickets.index'));
});

Breadcrumbs::register('admin.tickets.show', function (BreadcrumbsGenerator $crumbs, Ticket $ticket) {
    $crumbs->parent('admin.tickets.index');
    $crumbs->push($ticket->subject, route('admin.tickets.show', $ticket));
});

Breadcrumbs::register('admin.tickets.edit', function (BreadcrumbsGenerator $crumbs, Ticket $ticket) {
    $crumbs->parent('admin.tickets.show', $ticket);
    $crumbs->push('Редактировать', route('admin.tickets.edit', $ticket));
});

//-------------------------------------------------------------------------
// Admin.UsersController
//-------------------------------------------------------------------------

//admin.users.index
Breadcrumbs::register('admin.users.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Пользователи', route('admin.users.index'));
});

//admin.users.create
Breadcrumbs::register('admin.users.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.users.index');
    $crumbs->push('Создание пользователя', route('admin.users.create'));
});

//admin.users.show'
Breadcrumbs::register('admin.users.show', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.index');
    $crumbs->push($user->name, route('admin.users.show', $user));
});

//admin.users.edit
Breadcrumbs::register('admin.users.edit', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.show', $user);
    $crumbs->push('Редактирование пользователя', route('admin.users.edit', $user));
});



//-------------------------------------------------------------------------
// Adverts
//-------------------------------------------------------------------------

//adverts.index
Breadcrumbs::register('adverts.index', function (BreadcrumbsGenerator $crumbs, AdvertsPath $path = null) {
    $path = $path ?: new AdvertsPath();
    $crumbs->parent('adverts.inner_category', $path, $path);
});

//adverts.inner_category
Breadcrumbs::register('adverts.inner_category', function (BreadcrumbsGenerator $crumbs, AdvertsPath $path, AdvertsPath $orig) {
    if ($path->category && $parent = $path->category->parent) {
        $crumbs->parent('adverts.inner_category', $path->withCategory($path->category->parent), $orig);
    } else {

        $crumbs->parent('adverts.inner_region', $orig);
        if($path->category)
            $crumbs->push('Категории:', route('adverts.index', $path->withCategory(null)));
    }
    if ($path->category) {
        $crumbs->push($path->category->name, route('adverts.index', $path));
    }
});

//adverts.inner_region
Breadcrumbs::register('adverts.inner_region', function (BreadcrumbsGenerator $crumbs, AdvertsPath $path) {
    if ($path->region && $parent = $path->region->parent) {
        $crumbs->parent('adverts.inner_region', $path->withRegion($parent));
    } else {
        $crumbs->parent('home');
        $crumbs->push('Объявления', route('adverts.index'));
        if($path->region)
            $crumbs->push('Регионы:', route('adverts.index', $path->withRegion(null)));
    }
    if ($path->region) {
        $crumbs->push($path->region->name, route('adverts.index', $path));
    }
});

//adverts.show
Breadcrumbs::register('adverts.show', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push($advert->title, route('adverts.show', $advert));
});


//-------------------------------------------------------------------------
// Cabinet
//-------------------------------------------------------------------------

//cabinet.home
Breadcrumbs::register('cabinet.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Личный кабинет', route('cabinet.home'));
});


//-------------------------------------------------------------------------
// Cabinet.Adverts
//-------------------------------------------------------------------------

//cabinet.adverts.create.advert
Breadcrumbs::register('cabinet.adverts.create.advert', function (BreadcrumbsGenerator $crumbs, Category $category, Region $region = null) {
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push('Создание объявления');
});

//cabinet.adverts.create.category
Breadcrumbs::register('cabinet.adverts.create.category', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push('Создание объявления');
});

//cabinet.adverts.create.region
Breadcrumbs::register('cabinet.adverts.create.region', function (BreadcrumbsGenerator $crumbs, Category $category, Region $region = null) {
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push('Создание объявления');
});

//cabinet.adverts.edit
Breadcrumbs::register('cabinet.adverts.edit', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('cabinet.adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Редактирование объявления: ' . $advert->title, route('cabinet.adverts.edit', $advert));
});

//cabinet.adverts.index
Breadcrumbs::register('cabinet.adverts.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Мои объявления', route('cabinet.adverts.index'));
});

//cabinet.adverts.photos
Breadcrumbs::register('cabinet.adverts.photos', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Добавление фотографий' . $advert->title, route('cabinet.adverts.photos', $advert));
});

//-------------------------------------------------------------------------
// Cabinet.Banners
//-----------------------------------------------------------------------

//cabinet.banners.edit
Breadcrumbs::register('cabinet.banners.edit', function (BreadcrumbsGenerator $crumbs, Banner $banner) {
    $crumbs->parent('cabinet.banners.show', $banner);
    $crumbs->push('Редактирование баннера', route('cabinet.banners.edit', $banner));
});

//cabinet.banners.edit_fileit
Breadcrumbs::register('cabinet.banners.edit_file', function (BreadcrumbsGenerator $crumbs, Banner $banner) {
    $crumbs->parent('cabinet.banners.show', $banner);
    $crumbs->push('Изменить изображение', route('cabinet.banners.edit_file', $banner));
});


//cabinet.banners.show
Breadcrumbs::register('cabinet.banners.show', function (BreadcrumbsGenerator $crumbs, Banner $banner) {
    $crumbs->parent('cabinet.banners.index');
    $crumbs->push($banner->name, route('cabinet.banners.show', $banner));
});

//cabinet.banners.create
Breadcrumbs::register('cabinet.banners.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.banners.index');
    $crumbs->push('Создание баннера', route('cabinet.banners.create'));
});

//cabinet.banners.create.banner
Breadcrumbs::register('cabinet.banners.create.banner', function (BreadcrumbsGenerator $crumbs, Category $category, Region $region = null) {
    $crumbs->parent('cabinet.banners.create.region', $category, $region);
    $crumbs->push($region ? $region->name : 'Все регионы', route('cabinet.banners.create.banner', [$category, $region]));
});

//cabinet.banners.create.region
Breadcrumbs::register('cabinet.banners.create.region', function (BreadcrumbsGenerator $crumbs, Category $category, Region $region = null) {
    $crumbs->parent('cabinet.banners.create');
    $crumbs->push($category->name, route('cabinet.banners.create.region', [$category, $region]));
});

//cabinet.banners.index
Breadcrumbs::register('cabinet.banners.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Мои баннеры', route('cabinet.banners.index'));
});



//-------------------------------------------------------------------------
// Cabinet.Favorites
//-------------------------------------------------------------------------

Breadcrumbs::register('cabinet.favorites.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Моё избранное', route('cabinet.favorites.index'));
});

//-------------------------------------------------------------------------
// Cabinet.Profile
//-------------------------------------------------------------------------

//cabinet.profile.home
Breadcrumbs::register('cabinet.profile.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Мой профиль', route('cabinet.profile.home'));
});

//cabinet.profile.edit
Breadcrumbs::register('cabinet.profile.edit', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Редактирование профиля', route('cabinet.profile.edit'));
});

//cabinet.profile.phone
Breadcrumbs::register('cabinet.profile.phone', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Редактирование телефона', route('cabinet.profile.phone'));
});

//-------------------------------------------------------------------------
// Cabinet.Tickets
//-------------------------------------------------------------------------

//cabinet.tickets.index
Breadcrumbs::register('cabinet.tickets.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Мои заявки', route('cabinet.tickets.index'));
});

//cabinet.tickets.create
Breadcrumbs::register('cabinet.tickets.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.tickets.index');
    $crumbs->push('Создать заявку', route('cabinet.tickets.create'));
});

//cabinet.tickets.show
Breadcrumbs::register('cabinet.tickets.show', function (BreadcrumbsGenerator $crumbs, Ticket $ticket) {
    $crumbs->parent('cabinet.tickets.index');
    $crumbs->push($ticket->subject, route('cabinet.tickets.show', $ticket));
});


require_once('breadcrumbs/cabinet/breadcrumbs_cabinet_dialogs.php');


