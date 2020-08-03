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
    public function show(Advert $advert)
    {
        $user = Auth::user();
        return view('adverts.show', compact('advert', 'user'));
    }

    public function index(Region $region = null, Category $category = null)
    {
        $adverts =
        return view('adverts.index', compact('adverts'));
    }

}
