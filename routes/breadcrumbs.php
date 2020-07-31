<?php

use App\Entity\Attribute;
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

Breadcrumbs::register('login.phone', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Enter auth token', route('login.phone'));
});

Breadcrumbs::register('register', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Register', route('register'));
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
    $crumbs->parent('admin.users.show', $user);
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
    $crumbs->push('Create inner category');
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

//-------------------------------------------------------------------------
// AttributeController
//-------------------------------------------------------------------------
Breadcrumbs::register('admin.adverts.categories.attributes.create', function (BreadcrumbsGenerator $crumbs, Category $category){
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Create attribute');
});

Breadcrumbs::register('admin.adverts.categories.attributes.show', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute){
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('attribute: ' . $attribute->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
});

Breadcrumbs::register('admin.adverts.categories.attributes.edit', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute){
    $crumbs->parent('admin.adverts.categories.attributes.show', $category, $attribute);
    $crumbs->push('Edit');
});


//-------------------------------------------------------------------------
// Cabinet
//-------------------------------------------------------------------------
Breadcrumbs::register('cabinet.home', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Cabinet', route('cabinet.home'));
});

//-------------------------------------------------------------------------
// Cabinet - Profile
//-------------------------------------------------------------------------

Breadcrumbs::register('cabinet.profile.home', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('cabinet.home');
    $crumbs->push('Profile', route('cabinet.profile.home'));
});

Breadcrumbs::register('cabinet.profile.edit', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Edit', route('cabinet.profile.edit'));
});

Breadcrumbs::register('cabinet.profile.phone', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Edit', route('cabinet.profile.phone'));
});

//-------------------------------------------------------------------------
// Cabinet - Adverts
//-------------------------------------------------------------------------
Breadcrumbs::register('cabinet.adverts.index', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('cabinet.home');
    $crumbs->push('Adverts', route('cabinet.adverts.index'));
});


Breadcrumbs::register('cabinet.adverts.create.category', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push('Create advert', route('cabinet.adverts.create.category'));
});


Breadcrumbs::register('cabinet.adverts.create.region', function (BreadcrumbsGenerator $crumbs, Category $category, Region $region = null){
    $crumbs->parent('cabinet.adverts.index');
    $crumbs->push('Create advert', route('cabinet.adverts.create.region', $category, $region));
});
