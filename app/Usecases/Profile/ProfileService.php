<?php


namespace App\Usecases\Profile;


use App\Entity\User\User;
use App\Http\Requests\Cabinet\ProfileEditReruest;

class ProfileService
{
    public function edit(User $user, ProfileEditReruest $request): void
    {
        if ($user->phone !== $request['phone']) {
            $user->unverifyPhone();
        }
        $user->update($request->only('name', 'last_name', 'phone'));
    }
}
