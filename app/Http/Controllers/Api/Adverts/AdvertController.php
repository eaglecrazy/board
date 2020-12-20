<?php

namespace App\Http\Controllers\Api\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Http\Resources\Adverts\AdvertListResource;
use App\Http\Router\AdvertsPath;
use App\Services\PhoneFormatter;
use App\Usecases\Adverts\AdvertService;
use App\Usecases\Adverts\AdvertsSearchService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{
    private $search;

    public function __construct(AdvertsSearchService $search)
    {
        $this->search = $search;
    }

    public function advertsList(SearchRequest $request): AnonymousResourceCollection
    {
        $regionId = $request->get('region');
        $currentRegion = $regionId ? Region::findOrFail($regionId) : null;
        $categoryId = $request->get('category');
        $currentCategory = $categoryId ? Category::findOrFail($categoryId) : null;

        $searchResult = $this->search->search($currentCategory, $currentRegion, $request, 20, $request->get('page', 1));
//die(var_dump($searchResult->adverts));
        return AdvertListResource::collection($searchResult->adverts);
    }

    public function showAdvert(Advert $advert): AdvertDetailResource
    {
        if (!$advert->isAllowToShow()) {
            abort(403);
        }
        return new AdvertDetailResource($advert);
    }
}
