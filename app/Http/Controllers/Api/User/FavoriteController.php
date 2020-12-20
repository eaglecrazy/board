<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Usecases\Adverts\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    public function index(): AnonymousResourceCollection
    {
        $adverts = Advert::favoredByUser(Auth::user())->orderByDesc('id')->paginate(20);
        return AdvertDetailResource::collection($adverts);
    }

    public function remove(Advert $advert): JsonResponse
    {
        $this->service->remove(Auth::id(), $advert->id);
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
