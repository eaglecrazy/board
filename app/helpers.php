<?php

use App\Entity\Adverts\Category;
use App\Entity\Page;
use App\Entity\Region;
use App\Http\Router\AdvertsPath;


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
            ->make(AdvertsPath::class)
            ->withPage($page);
    }
}
