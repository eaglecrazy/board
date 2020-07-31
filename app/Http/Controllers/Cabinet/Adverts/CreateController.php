<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Category;
use App\Entity\Region;
use App\Http\Middleware\FilledProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
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
}
