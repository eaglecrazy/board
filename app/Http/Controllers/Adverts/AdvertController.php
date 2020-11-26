<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Services\PhoneFormatter;
use App\Usecases\Adverts\AdvertService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Router\AdvertsPath;

class AdvertController extends Controller
{
//    public function index(Region $region = null, Category $category = null)
    public function path(AdvertsPath $path)
    {
        //получим и отфильтруем объявления
        //фильтр по категории и дочерним категориям
        $query = Advert::active()->with('category', 'region')->orderByDesc('published_at');
        if ($currentCategory = $path->category) {
            $query->forCategory($currentCategory);
        }
        //фильтр по этому региону и дочерним регионам
        if ($currentRegion = $path->region) {
            $query->forRegion($currentRegion);
        }
        $adverts = $query->paginate(20);

        //получим дочение регионы и категории
        $childernRegions = $currentRegion
            ? $currentRegion->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $childernCategories = $currentCategory
            ? $currentCategory->children()->defaultOrder()->getModels()
            : Category::whereIsRoot()->defaultOrder()->getModels();

//        return view('adverts.index', compact('adverts', 'currentCategory', 'currentRegion', 'childernRegions', 'childernCategories'));
        return view('adverts.index', compact('adverts', 'path', 'childernRegions', 'childernCategories'));
    }

    public function show(Advert $advert)
    {
        if (!$advert->isAllowToShow()) {
            abort(403);
        }

        $service = new AdvertService();
        $similar = $service->getSimilar($advert);
        return view('adverts.show', compact('advert', 'similar'));
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
