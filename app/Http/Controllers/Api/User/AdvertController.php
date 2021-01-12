<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\AdvertContentEditRequest;
use App\Http\Requests\Adverts\AddPhotosRequest;
use App\Http\Resources\Adverts\AdvertDetailResource;
use App\Http\Resources\Adverts\AdvertListResource;
use App\Usecases\Adverts\AdvertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    //todo сделать метод добавления фоточек, метод редактирования фоточек


    private $service;

    public function __construct(AdvertService $service)
    {
        $this->service = $service;
    }

    //ПРОВЕРИТЬ РАБОТУ
    public function index(): AnonymousResourceCollection
    {
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);
        return AdvertListResource::collection($adverts);
    }

    public function store(CreateRequest $request, Category $category, Region $region = null): JsonResponse
    {
            $advert = $this->service->create(
                Auth::user(),
                $category,
                $region,
                $request
            );
        return (new AdvertDetailResource($advert))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(AdvertContentEditRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->service->edit($advert, $request);
        return new AdvertDetailResource($advert);
    }

    //todo так как я не менял атрибуты вообще, то нужно проверить как работает их редактирование
    public function updateAttributes(AttributesRequest $request, Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->service->editAttributes($advert, $request);
        return new AdvertDetailResource($advert);
    }

    public function sendToModeration(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->service->sendToModeration($advert);
        return new AdvertDetailResource($advert);
    }

    public function close(Advert $advert): AdvertDetailResource
    {
        $this->checkAccess($advert);
        $this->service->close($advert);
        return new AdvertDetailResource($advert);
    }

    public function destroy(Advert $advert): JsonResponse
    {
        $this->checkAccess($advert);
        $this->service->remove($advert);
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function checkAccess(Advert $advert): void
    {
        if (!Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }

}
