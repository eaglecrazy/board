<?php

use App\Entity\Region;
use App\Entity\Category;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Entity\User;
use Illuminate\Http\Request;


//-------------------------------------------------------------------------
//Home
//-------------------------------------------------------------------------
Breadcrumbs::register('home', function (BreadcrumbsGenerator $crumbs){
    $crumbs->push('Home', route('home'));
});

//-------------------------------------------------------------------------
//Аутентификация, регистрация, ЛК
//-------------------------------------------------------------------------
Breadcrumbs::register('login', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Login', route('login'));
});

Breadcrumbs::register('register', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Register', route('register'));
});

Breadcrumbs::register('cabinet', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Cabinet', route('cabinet'));
});

Breadcrumbs::register('password.request', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('login');
    $crumbs->push('Input email', route('password.request'));
});

Breadcrumbs::register('password.reset', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('password.request');
    $crumbs->push('Change', route('password.reset'));
});

//-------------------------------------------------------------------------
//Admin
//-------------------------------------------------------------------------
Breadcrumbs::register('admin.home', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Admin', route('admin.home'));
});

//-------------------------------------------------------------------------
//UsersController
//-------------------------------------------------------------------------
Breadcrumbs::register('admin.users.index', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.home');
    $crumbs->push('Users', route('admin.users.index'));
});

Breadcrumbs::register('admin.users.create', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.users.index');
    $crumbs->push('Create', route('admin.users.create'));
});

Breadcrumbs::register('admin.users.show', function (BreadcrumbsGenerator $crumbs, User $user){
    $crumbs->parent('admin.users.index');
    $crumbs->push($user->name, route('admin.users.show', $user));
});

Breadcrumbs::register('admin.users.edit', function (BreadcrumbsGenerator $crumbs, User $user){
    $crumbs->parent('admin.users.index');
    $crumbs->push('Edit', route('admin.users.edit', $user));
});


//-------------------------------------------------------------------------
//RegionsController
//-------------------------------------------------------------------------
Breadcrumbs::register('admin.regions.index', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.home');
    $crumbs->push('Regions', route('admin.regions.index'));
});

Breadcrumbs::register('admin.regions.create', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.regions.index');
    $crumbs->push('Create root region', route('admin.regions.create'));
});

Breadcrumbs::register('admin.regions.create-inner', function (BreadcrumbsGenerator $crumbs, Region $region){
    $crumbs->parent('admin.regions.show', $region);
    $crumbs->push('Create inner region', route('admin.regions.create'));
});

Breadcrumbs::register('admin.regions.show', function (BreadcrumbsGenerator $crumbs, Region $region){
    if($parent = $region->parent)
        $crumbs->parent('admin.regions.show', $parent);
    else
        $crumbs->parent('admin.regions.index');
    $crumbs->push($region->name, route('admin.regions.show', $region));
});

Breadcrumbs::register('admin.regions.edit', function (BreadcrumbsGenerator $crumbs, Region $region){
    $crumbs->parent('admin.regions.show', $region);
    $crumbs->push('Edit', route('admin.regions.edit', $region));
});


//-------------------------------------------------------------------------
//CategoriesController
//-------------------------------------------------------------------------
Breadcrumbs::register('admin.adverts.categories.index', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.home');
    $crumbs->push('Categories', route('admin.adverts.categories.index'));
});

Breadcrumbs::register('admin.adverts.categories.create', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.adverts.categories.index');
    $crumbs->push('Create category', route('admin.adverts.categories.create'));
});

Breadcrumbs::register('admin.adverts.categories.create-inner', function (BreadcrumbsGenerator $crumbs, Category $category){
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Create inner category', route('admin.adverts.categories.create'));
});


Breadcrumbs::register('admin.adverts.categories.show', function (BreadcrumbsGenerator $crumbs, Category $category){
    if($parent = $category->parent)
        $crumbs->parent('admin.adverts.categories.show', $parent);
    else
        $crumbs->parent('admin.adverts.categories.index');
    $crumbs->push($category->name, route('admin.adverts.categories.show', $category));
});

Breadcrumbs::register('admin.adverts.categories.edit', function (BreadcrumbsGenerator $crumbs, Category $category){
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Edit', route('admin.adverts.categories.edit', $category));
});
