<?php
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use App\Entity\User;

Breadcrumbs::register('home', function (BreadcrumbsGenerator $crumbs){
    $crumbs->push('Home', route('home'));
});


//Аутентификация, регистрация, ЛК
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

//Админка
Breadcrumbs::register('admin.home', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('home');
    $crumbs->push('Admin', route('admin.home'));
});


//UsersController
Breadcrumbs::register('admin.user.index', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.home');
    $crumbs->push('Users', route('admin.users.index'));
});

Breadcrumbs::register('admin.user.create', function (BreadcrumbsGenerator $crumbs){
    $crumbs->parent('admin.user.index');
    $crumbs->push('Create', route('admin.users.create'));
});

Breadcrumbs::register('admin.user.show', function (BreadcrumbsGenerator $crumbs, User $user){
    $crumbs->parent('admin.user.index');
    $crumbs->push($user->name, route('admin.users.show', $user));
});

Breadcrumbs::register('admin.user.edit', function (BreadcrumbsGenerator $crumbs, User $user){
    $crumbs->parent('admin.user.index');
    $crumbs->push('Edit', route('admin.users.edit', $user));
});
