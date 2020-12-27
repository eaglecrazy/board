<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index(){
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);
        $statuses = Advert::statusesList();
        return view('cabinet.adverts.index', compact('adverts', 'statuses'));
    }
}
