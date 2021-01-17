<?php

use App\Entity\Adverts\Advert\Advert;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//-------------------------------------------------------------------------
// Cabinet.Dialogs
//-------------------------------------------------------------------------
Breadcrumbs::register('cabinet.dialogs.dialog', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.show', $advert);
    $crumbs->push('Сообщения');
});


Breadcrumbs::register('cabinet.dialogs.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Сообщения', route('cabinet.dialogs.index'));
});
