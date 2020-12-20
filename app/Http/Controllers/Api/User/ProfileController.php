<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileEditReruest;
use App\Http\Resources\User\ProfileResource;
use App\Usecases\Profile\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $editService;

    public function __construct(ProfileService $editService)
    {
        $this->editService = $editService;
    }

    public function show(Request $request): ProfileResource
    {
        return new ProfileResource($request->user());
    }

    public function update(ProfileEditReruest $request): ProfileResource
    {
        $user = $request->user();
        $this->editService->edit($user, $request);
        return new ProfileResource($request->user());
    }
}
