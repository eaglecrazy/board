<?php

namespace App\Http\Controllers\Api\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\SearchRequest;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Http\Resources\Adverts\AdvertListResource;
use App\Usecases\Adverts\AdvertsSearchService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Swagger\Annotations as SWG;

class AdvertController extends Controller
{
    private $search;

    public function __construct(AdvertsSearchService $search)
    {
        $this->search = $search;
    }

    /**
     * @SWG\Get(
     *     path="/adverts",
     *     tags={"Adverts"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/AdvertList")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param SearchRequest $request
     * @return AnonymousResourceCollection
     */
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


    /**
     * @SWG\Get(
     *     path="/adverts/{advertId}",
     *     tags={"Adverts"},
     *     @SWG\Parameter(
     *         name="advertId",
     *         description="ID of advert",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/AdvertDetail"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param Advert $advert
     * @return AdvertDetailResource
     */
    public function showAdvert(Advert $advert): AdvertDetailResource
    {
        if (!$advert->isAllowToShow()) {
            abort(403);
        }
        return new AdvertDetailResource($advert);
    }
}
