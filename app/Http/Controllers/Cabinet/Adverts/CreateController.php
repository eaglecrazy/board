<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Adverts\CreateRequest;
use App\Usecases\Adverts\AdvertService;
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
        $pageTitle = 'Выбор категории';
        return view('cabinet.adverts.create.category', compact('categories', 'pageTitle'));
    }

    public function region(Category $category, Region $region = null)
    {
        $innerRegionsQuery = Region::where('parent_id', $region ? $region->id : null)->orderBy('name');
        $innerRegions = $innerRegionsQuery->get();
        if($innerRegions->isEmpty()){
            return redirect()->route('cabinet.adverts.create.advert', compact('category', 'region'));
        }

        if(empty($region)){
            $importantRegions = $innerRegionsQuery->important()->getModels();
        } else {
            $importantRegions = null;
        }
        $pageTitle = 'Выбор региона';


        return view('cabinet.adverts.create.region', compact('category', 'region', 'innerRegions', 'importantRegions', 'pageTitle'));
    }

    public function advert(Category $category, Region $region = null)
    {
        $pageTitle = 'Создание объявления';
        return view('cabinet.adverts.create.advert', compact('category', 'region', 'pageTitle'));
    }

    public function store(CreateRequest $request, Category $category, Region $region)
    {
        try {
            $advert = $this->service->create(
                Auth::user(),
                $category,
                $region,
                $request
            );
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }
}
