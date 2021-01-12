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
use App\Usecases\Adverts\AdvertsPhotoService;
use App\Usecases\Adverts\AdvertsSearchService;
use http\Url;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;


class AdvertController extends Controller
{
    private $searchService;
    private $photosService;

    public function __construct(AdvertsSearchService $search, AdvertsPhotoService $photos)
    {
        $this->searchService = $search;
        $this->photosService = $photos;
    }

    public function path(SearchRequest $request, AdvertsPath $path)
    {
//        dd($request->get('page'));
        $currentRegion = $path->region;
        $currentCategory = $path->category;

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

        $photos = $this->photosService->getPhotosArray($adverts->items());

        return view('adverts.index', compact(
            'adverts', 'photos',
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
        $photos = $advert->getPhotosLinks();
        $similarPhotos = $this->photosService->getPhotosArray($similar->toArray());
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
