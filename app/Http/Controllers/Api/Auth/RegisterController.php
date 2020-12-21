<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Usecases\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;


class RegisterController extends Controller
{
    private $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    /**
     * @SWG\Post(
     *     path="/register",
     *     tags={"Profile"},
     *     @SWG\Parameter(name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/RegisterRequest")),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     )
     * )
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->service->register($request);
        return response()
            ->json(['success' => 'Check your email and click on the link to verify.'])
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
