<?php

namespace App\Http\Controllers;

use App\Entity\Banner\Banner;
use App\Usecases\Banners\BannerService;
use App\Usecases\Banners\BannersSearchService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private $search;
    private $service;

    public function __construct(BannersSearchService $search, BannerService $service)
    {
        $this->search = $search;
        $this->service = $service;
    }

    public function get(Request $request)
    {
        $format = $request['format'];
        $category = $request['category'];
        $region = $request['region'];

        if (!$banner = $this->search->getRandomForView($category, $region, $format)) {
            return '';
        }
        return view('banner.get', compact('banner'));
    }

    public function click(Banner $banner)
    {
        $this->service->click($banner);
        return redirect($banner->url);
    }
}
