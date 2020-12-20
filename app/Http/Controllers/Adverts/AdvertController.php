<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Router\AdvertsPath;
use App\Services\PhoneFormatter;
use App\Usecases\Adverts\AdvertService;
use App\Usecases\Adverts\AdvertsSearchService;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{
    private $search;

    public function __construct(AdvertsSearchService $search)
    {
        $this->search = $search;
    }

    public function path(SearchRequest $request, AdvertsPath $path)
    {
        $currentRegion = $path->region;
        $currentCategory = $path->category;

        $searchResult = $this->search->search($currentCategory, $currentRegion, $request, 20, $request->get('page', 1));

        $adverts = $searchResult->adverts;
        $regionsCounts = $searchResult->regionsCounts;
        $categoriesCounts = $searchResult->categoriesCounts;

        $searchAttributes = $path->category ? $path->category->allAttributes() : null;

        //получим дочение регионы и категории c ограничением тех р. и к. где найдены объявления
        $childernRegions = $currentRegion
            ? $currentRegion->children()->orderBy('name')->whereIn('id', array_keys($regionsCounts))->getModels()
            : Region::roots()->orderBy('name')->whereIn('id', array_keys($regionsCounts))->getModels();

        $childernCategories = $currentCategory
            ? $currentCategory->children()->defaultOrder()->whereIn('id', array_keys($categoriesCounts))->getModels()
            : Category::whereIsRoot()->defaultOrder()->whereIn('id', array_keys($categoriesCounts))->getModels();

//        dd($childernRegions);
//        dd($childernCategories);



        return view('adverts.index', compact(
            'adverts',
            'path',
            'searchAttributes',
            'childernRegions', 'childernCategories',
            'regionsCounts', 'categoriesCounts'));
    }

    public function show(Advert $advert)
    {
        if (!$advert->isAllowToShow()) {
            abort(403);
        }

        $service = new AdvertService();
        $similar = $service->getSimilar($advert);
        $user = Auth::user();
        return view('adverts.show', compact('advert', 'similar', 'user'));
    }

    public function phone(Advert $advert): string
    {
        if (!$advert->isAllowToShow()) {
            abort(403);
        }

        $formatter = new PhoneFormatter();
        return $formatter->phone_format($advert->user->phone);
    }
}
