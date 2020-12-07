<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Banner\Banner;
use App\Http\Middleware\FilledProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index()
    {
//        $banners = Banner::forUser(Auth::user())->orderByDesc('id')->paginate(20);
        $banners = [];
        return view('cabinet.banners.index', compact('banners'));
    }
}
