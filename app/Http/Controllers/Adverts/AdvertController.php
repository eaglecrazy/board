<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Requests\Adverts\SearchRequest;
use App\Services\PhoneFormatter;
use App\Usecases\Adverts\AdvertService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Router\AdvertsPath;
use App\Usecases\Adverts\SearchService;

class AdvertController extends Controller
{
    private $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    public function path(SearchRequest $request, AdvertsPath $path)
    {
        $currentRegion = $path->region;
        $currentCategory = $path->category;

        //получим дочение регионы и категории
        $childernRegions = $currentRegion
            ? $currentRegion->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $childernCategories = $currentCategory
            ? $currentCategory->children()->defaultOrder()->getModels()
            : Category::whereIsRoot()->defaultOrder()->getModels();

        $adverts = $this->search->search($currentCategory, $currentRegion, $request, 20, $request->get('page', 1));

//        //получим и отфильтруем объявления
//        //фильтр по категории и дочерним категориям
//        $query = Advert::active()->with('category', 'region')->orderByDesc('published_at');
//        if ($currentCategory = $path->category) {
//            $query->forCategory($currentCategory);
//        }
//        //фильтр по этому региону и дочерним регионам
//        if ($currentRegion = $path->region) {
//            $query->forRegion($currentRegion);
//        }
//
//        $adverts = $query->paginate(20);


        return view('adverts.index', compact('adverts', 'path', 'childernRegions', 'childernCategories'));
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
