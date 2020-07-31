<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Category;
use App\Entity\Region;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Admin\Users\CreateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{
    private $service;

    public function __construct(AdvertService $service)
    {
        $this->middleware(FilledProfile::class);
        $this->service = $service;
    }

    public function category()
    {
        //toTree сразу возвращается дерево категорий, и при запросе childern одной категории SQL запросы уже не выполняются
        $categories = Category::defaultOrder()->withDepth()->get()->toTree();
        return view('cabinet.adverts.create.category', compact('categories'));
    }

    public function region(Category $category, Region $region = null)
    {
        $regions = Region::where('parent_id', $region ? $region->id : null)
            ->orderBy('name')
            ->get();
        return view('cabinet.adverts.create.region', compact('category', 'region', 'regions'));
    }

    public function advert(Category $category, Region $region = null)
    {
        return view('cabinet.adverts.create.advert', compact('category', 'region'));
    }

    public function store(CreateRequest $request, Category $category, Region $region)
    {
        try {
            $advert = $this->service->create(
                Auth::id(),
                $category->id,
                $region ? $region->id : null,
                $request
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }
}
