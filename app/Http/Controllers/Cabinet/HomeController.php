<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $pageTitle = 'Личный кабинет';
        return view('cabinet.home', compact('pageTitle'));
    }
}
