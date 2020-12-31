<?php

namespace App\Http\Controllers;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class HomeController extends Controller
{
    public function index()
    {



        $regions = Region::roots()->orderBy('name')->getModels();
        $categories = Category::whereIsRoot()->defaultOrder()->getModels();
        return view('home', compact('regions', 'categories'));
    }
}
