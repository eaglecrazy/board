<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin-panel');
    }

    public function index()
    {
        $pageTitle = 'Админка';
        return view('admin.home', compact('pageTitle'));
    }
}
