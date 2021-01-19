<?php

use App\Entity\Adverts\Category;
use App\Entity\Page;
use App\Entity\Region;
use App\Http\Router\AdvertsPath;
use App\Http\Router\PagePath;
use Carbon\Carbon;


if(!function_exists('adPath')){
    function adPath(?Region $region, ?Category $category){
        return app()
            ->make(AdvertsPath::class)
            ->withRegion($region)
            ->withCategory($category);
    }
}


if(!function_exists('pagePath')){
    function pagePath(?Page $page){
        return app()
            ->make(PagePath::class)
            ->withPage($page);
    }
}


if(!function_exists('dtFormat')){
    function dtFormat(Carbon $dt){
        return $dt->format('Y.m.d - H:i');
    }
}

if(!function_exists('dFormat')){
    function dFormat(Carbon $d){
        return $d->format('d.m.Y');
    }
}
