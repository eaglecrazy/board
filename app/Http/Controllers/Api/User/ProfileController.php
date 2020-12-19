<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileEditReruest;
use App\Usecases\Profile\ProfileService;
use Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    private ProfileService $editService;

    public function __construct(ProfileService $service)
    {
        $this->editService = $service;
    }

    public function show(): User
    {
        return Auth::user();
    }

    public function update(ProfileEditReruest $request): JsonResponse
    {
        $user = $request->user();
        $this->editService->edit($user, $request);
        return response()
            ->json($user)
            ->setStatusCode(Response::HTTP_OK);
    }
}
