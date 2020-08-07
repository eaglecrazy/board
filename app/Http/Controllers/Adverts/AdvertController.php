<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{
    public function index(Region $region = null, Category $category = null)
    {
        //получим и отфильтруем объявления
        $query = Advert::with('category', 'region')->orderByDesc('id');
        if($category){
            $query->forCategory($category);
        }
        if($region){
            $query->forRegion($region);
        }
        $adverts = $query->paginate(20);

        //получим дочение регионы и категории
        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();
        $categories = $category
        ? $category->children->defaultOrder()->getModels()
        : Category::whereIsRoot()->defaultOrder()->getModels();

        return view('adverts.index', compact('adverts', 'category', 'region', 'regions', 'categories'));
    }

    public function show(Advert $advert)
    {
        if(!$advert->isAllowToShow()){
            abort(403);
        }

        return view('adverts.show', compact('advert'));
    }



}
