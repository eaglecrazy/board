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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;


class AdvertController extends Controller
{
    private $searchService;
    private $advertService;

    public function __construct(AdvertsSearchService $search, AdvertService $advert)
    {
        $this->searchService = $search;
        $this->advertService = $advert;
    }

    public function path(SearchRequest $request, AdvertsPath $path)
    {
        $currentRegion = $path->region;
        $currentCategory = $path->category;

//        dd($currentRegion);

        $searchResult = $this->searchService->search($currentCategory, $currentRegion, $request, 20, $request->get('page', 1));


        /** @var LengthAwarePaginator $adverts */
        $adverts = $searchResult->adverts;
        $adverts->setPath($request->url());

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

        $photos = $this->advertService->getPhotosArray($adverts->items());

        return view('adverts.index', compact(
            'adverts', 'photos',
            'path',
            'searchAttributes',
            'childernRegions', 'childernCategories',
            'currentRegion', 'currentCategory',
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
        $photos = $advert->getPhotosLinks();
        $similarPhotos = $this->advertService->getPhotosArray($similar->toArray());
        return view('adverts.show.show', compact('advert', 'similar', 'user', 'photos', 'similarPhotos'));
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
