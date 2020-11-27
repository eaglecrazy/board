<?php
use App\Entity\Region;
use App\Entity\Adverts\Category;
use App\Http\Router\AdvertsPath;


if(!function_exists('adPath')){
    function adPath(?Region $region, ?Category $category){
        return app()
            ->make(AdvertsPath::class)
            ->withRegion($region)
            ->withCategory($category);
    }
}
