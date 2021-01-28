<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Usecases\Adverts\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    /**
     * @SWG\Get(
     *     path="/user/favorites",
     *     tags={"Favorites"},
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
     */
    public function index(): AnonymousResourceCollection
    {
        $adverts = Advert::favoredByUser(Auth::user())->orderByDesc('id')->paginate(20);
        return AdvertDetailResource::collection($adverts);
    }

    /**
     * @SWG\Delete(
     *     path="/user/favorites/{advertId}",
     *     tags={"Favorites"},
     *     @SWG\Parameter(name="advertId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param Advert $advert
     * @return JsonResponse
     */
    public function remove(Advert $advert): JsonResponse
    {
        $this->service->remove(Auth::id(), $advert->id);
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
