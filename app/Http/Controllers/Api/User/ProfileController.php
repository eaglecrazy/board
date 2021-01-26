<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileEditReruest;
use App\Http\Resources\User\ProfileResource;
use App\Usecases\Profile\ProfileService;
use Illuminate\Http\Request;
use Swagger\Annotations as SWG;

class ProfileController extends Controller
{
    private $editService;

    public function __construct(ProfileService $editService)
    {
        $this->editService = $editService;
    }

    /**
     * @SWG\Get(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param Request $request
     * @return ProfileResource
     */
    public function show(Request $request): ProfileResource
    {
        return new ProfileResource($request->user());
    }

    /**
     * @SWG\Put(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Parameter(name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/ProfileEditRequest")),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param ProfileEditReruest $request
     * @return ProfileResource
     */
    public function update(ProfileEditReruest $request): ProfileResource
    {
        $user = $request->user();
        $this->editService->edit($user, $request);
        return new ProfileResource($request->user());
    }
}
