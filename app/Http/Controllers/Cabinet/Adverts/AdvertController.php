<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index(Request $request){

        $query = Advert::forUser(Auth::user())->orderByDesc('updated_at');

        if(!empty($value = $request->get('title'))){
            $query->where('title', 'like', '%' . $value . '%');
        }

        if(!empty($value = $request->get('region'))){
            $region_ids = Region::where('name', 'like', '%' . $value . '%')->get()->pluck('id');
            $query->whereIn('region_id', $region_ids);
        }

        if(!empty($value = $request->get('category'))){
            $categories_ids = Category::where('name', 'like', '%' . $value . '%')->get()->pluck('id');
            $query->whereIn('category_id', $categories_ids);
        }

        if(!empty($value = $request->get('status'))){
            $query->where('status', $value);
        }

        $adverts = $query->paginate(20);
        $statuses = Advert::statusesList();
        return view('cabinet.adverts.index', compact('adverts', 'statuses'));
    }
}
