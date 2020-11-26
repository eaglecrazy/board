<?php
use App\Entity\Adverts\Advert\Advert;
use App\Http\Router\AdvertsPath;


if(!function_exists('adPath')){
    function adPath(Advert $advert){
        return app()
            ->make(AdvertsPath::class)
            ->withRegion($advert->region)
            ->withCategory($advert->category);
    }
}
