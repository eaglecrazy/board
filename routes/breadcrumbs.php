<?php

use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Advert\Advert;
use App\Entity\Region;
use App\Entity\Adverts\Category;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Entity\User;
use Illuminate\Http\Request;
use App\Http\Router\AdvertsPath;


//-------------------------------------------------------------------------
//Home
//-------------------------------------------------------------------------

//home
Breadcrumbs::register('home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Home', route('home'));
});


//-------------------------------------------------------------------------
//Аутентификация, регистрация, ЛК
//-------------------------------------------------------------------------

//login
Breadcrumbs::register('login', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Login', route('login'));
});

//login.phone
Breadcrumbs::register('login.phone', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Enter auth token', route('login.phone'));
});

//register
Breadcrumbs::register('register', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Register', route('register'));
});

//password.request
Breadcrumbs::register('password.request', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('login');
    $crumbs->push('Input email', route('password.request'));
});

//password.reset
Breadcrumbs::register('password.reset', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('password.request');
    $crumbs->push('Change', route('password.reset'));
});


//-------------------------------------------------------------------------
//Admin
//-------------------------------------------------------------------------

//admin.home
Breadcrumbs::register('admin.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Admin', route('admin.home'));
});


//-------------------------------------------------------------------------
// Admin.Adverts.AdvertController
//-------------------------------------------------------------------------
Breadcrumbs::register('admin.adverts.adverts.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Adverts', route('admin.adverts.adverts.index'));
});

//-------------------------------------------------------------------------
// Admin.Adverts.AttributeController
//-------------------------------------------------------------------------

//admin.adverts.categories.attributes.create
Breadcrumbs::register('admin.adverts.categories.attributes.create', function (BreadcrumbsGenerator $crumbs, Category $category) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Create attribute');
});

//admin.adverts.categories.attributes.show
Breadcrumbs::register('admin.adverts.categories.attributes.show', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('attribute: ' . $attribute->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
});

//admin.adverts.categories.attributes.edit
Breadcrumbs::register('admin.adverts.categories.attributes.edit', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute) {
    $crumbs->parent('admin.adverts.categories.attributes.show', $category, $attribute);
    $crumbs->push('Edit attribute');
});


//-------------------------------------------------------------------------
// Admin.Adverts.ManageController
//-------------------------------------------------------------------------

//admin.adverts.adverts.edit
Breadcrumbs::register('admin.adverts.adverts.edit', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Edit: ' . $advert->title, route('cabinet.adverts.edit', $advert));
});

//admin.adverts.adverts.photos
Breadcrumbs::register('admin.adverts.adverts.photos', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Add photos: ' . $advert->title, route('cabinet.adverts.photos', $advert));
});

//admin.adverts.adverts.reject
Breadcrumbs::register('admin.adverts.adverts.reject', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.show', $advert);
    $crumbs->push('Reason for rejection', route('admin.adverts.adverts.reject', $advert));
});


//-------------------------------------------------------------------------
// Admin.CategoriesController
//-------------------------------------------------------------------------

//admin.adverts.categories.index
Breadcrumbs::register('admin.adverts.categories.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Categories', route('admin.adverts.categories.index'));
});

//admin.adverts.categories.index
Breadcrumbs::register('admin.adverts.categories.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.adverts.categories.index');
    $crumbs->push('Create category', route('admin.adverts.categories.create'));
});

//admin.adverts.categories.create-inner
Breadcrumbs::register('admin.adverts.categories.create-inner', function (BreadcrumbsGenerator $crumbs, Category $category) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Create inner category');
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
    $crumbs->push('Edit category', route('admin.adverts.categories.edit', $category));
});


//-------------------------------------------------------------------------
// Admin.RegionsController
//-------------------------------------------------------------------------

//admin.regions.index
Breadcrumbs::register('admin.regions.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Regions', route('admin.regions.index'));
});

//admin.regions.create
Breadcrumbs::register('admin.regions.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.regions.index');
    $crumbs->push('Create root region', route('admin.regions.create'));
});

//admin.regions.create-inner
Breadcrumbs::register('admin.regions.create-inner', function (BreadcrumbsGenerator $crumbs, Region $region) {
    $crumbs->parent('admin.regions.show', $region);
    $crumbs->push('Create inner region', route('admin.regions.create'));
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
    $crumbs->push('Edit', route('admin.regions.edit', $region));
});


//-------------------------------------------------------------------------
// Admin.UsersController
//-------------------------------------------------------------------------

//admin.users.index
Breadcrumbs::register('admin.users.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Users', route('admin.users.index'));
});

//admin.users.create
Breadcrumbs::register('admin.users.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.users.index');
    $crumbs->push('Create', route('admin.users.create'));
});

//admin.users.show'
Breadcrumbs::register('admin.users.show', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.index');
    $crumbs->push($user->name, route('admin.users.show', $user));
});

//admin.users.edit
Breadcrumbs::register('admin.users.edit', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.show', $user);
    $crumbs->push('Edit', route('admin.users.edit', $user));
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
    $crumbs->push('Cabinet', route('cabinet.home'));
});


//-------------------------------------------------------------------------
// Cabinet.Adverts
//-------------------------------------------------------------------------

//cabinet.adverts.create.advert
Breadcrumbs::register('cabinet.adverts.create.advert', function (BreadcrumbsGenerator $crumbs, Category $category, Region $region = null) {
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push($category->name . ' / ' . ($region ? $region->name : 'All regions'), route('cabinet.adverts.create.advert', $category, $region));
});

//cabinet.adverts.create.category
Breadcrumbs::register('cabinet.adverts.create.category', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push('Create advert', route('cabinet.adverts.create.category'));
});

//cabinet.adverts.create.region
Breadcrumbs::register('cabinet.adverts.create.region', function (BreadcrumbsGenerator $crumbs, Category $category, Region $region = null) {
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push($category->name, route('cabinet.adverts.create.region', $category, $region));
});

//cabinet.adverts.edit
Breadcrumbs::register('cabinet.adverts.edit', function (BreadcrumbsGenerator $crumbs, Advert $advert) {

    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Edit: ' . $advert->title, route('cabinet.adverts.edit', $advert));
});

//cabinet.adverts.index
Breadcrumbs::register('cabinet.adverts.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Adverts', route('cabinet.adverts.index'));
});

//cabinet.adverts.photos
Breadcrumbs::register('cabinet.adverts.photos', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', adPath($advert->region, $advert->category));
    $crumbs->push('Add photos: ' . $advert->title, route('cabinet.adverts.photos', $advert));
});


//-------------------------------------------------------------------------
// Cabinet.Favorites
//-------------------------------------------------------------------------

Breadcrumbs::register('cabinet.favorites.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Adverts', route('cabinet.favorites.index'));
});

//-------------------------------------------------------------------------
// Cabinet.Profile
//-------------------------------------------------------------------------

//cabinet.profile.home
Breadcrumbs::register('cabinet.profile.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Profile', route('cabinet.profile.home'));
});

//cabinet.profile.edit
Breadcrumbs::register('cabinet.profile.edit', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Edit', route('cabinet.profile.edit'));
});

//cabinet.profile.phone
Breadcrumbs::register('cabinet.profile.phone', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Edit', route('cabinet.profile.phone'));
});
