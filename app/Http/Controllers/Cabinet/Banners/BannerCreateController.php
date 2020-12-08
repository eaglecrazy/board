<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Adverts\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerCreateRequest;
use App\Usecases\Banners\BannerService;
use Illuminate\Support\Facades\Auth;


class BannerCreateController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function category()
    {
        //toTree сразу возвращается дерево категорий, и при запросе childern одной категории SQL запросы уже не выполняются
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();
        return view('cabinet.banners.create.category', compact('categories'));
    }

    public function region(Category $category, Region $region = null)
    {
        $innerRegions = Region::where('parent_id', $region ? $region->id : null)
            ->orderBy('name')
            ->get();
        return view('cabinet.banners.create.region', compact('category', 'region', 'innerRegions'));
    }

    public function banner(Category $category, Region $region = null)
    {
        $formats = Banner::formatsList();
        return view('cabinet.banners.create.banner', compact('category', 'region', 'formats'));
    }

    public function store(BannerCreateRequest $request, Category $category, Region $region): \Illuminate\Http\RedirectResponse
    {
        try {
            $banner = $this->service->create(
                Auth::user(),
                $category,
                $region,
                $request
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.banners.show', $this->banner());
    }
}
