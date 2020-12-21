<?php

namespace App\Http\Controllers\Api\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Usecases\Adverts\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Swagger\Annotations as SWG;

class FavoriteController extends Controller
{
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    /**
     * @SWG\Post(
     *     path="/adverts/{advertId}/favorite",
     *     tags={"Adverts"},
     *     @SWG\Parameter(name="advertId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param Advert $advert
     * @return JsonResponse
     */
    public function add(Advert $advert): JsonResponse
    {
        $this->service->add(Auth::id(), $advert->id);
        return response()->json([], Response::HTTP_CREATED);
    }


    /**
     * @SWG\Delete(
     *     path="/adverts/{advertId}/favorite",
     *     tags={"Adverts"},
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
