<?php


namespace App\Usecases\Adverts;


use App\Entity\Adverts\Advert\Advert;
use App\Http\Requests\Adverts\CreateRequest;
use App\Entity\Adverts\Category;
use App\Entity\Region;

class AdvertService
{
    public function __construct()
    {
    }

    public function create($user, $category, $region, CreateRequest $request) : Advert
    {
        $advert = Advert::make([
            'title' => $request['title'],
            'content' => $request['content'],
            'price' => $request['price'],
            'address' => $request['address'],
            'status' => Advert::STATUS_DRAFT,
        ]);

        $advert->user()->associate($user);
        $advert->category()->associate($category);
        $advert->region()->associate($region);

        foreach($category->allAttributes() as $attribute){
            $value = $request['attributes'][$attribute->id] ?? null;

        }

//        dd($advert);

        $advert->saveOrFail();

        return $advert;
    }
}
