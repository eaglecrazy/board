<?php

namespace App\Http\Controllers\Auth\Services;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FacebookController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback(){
        $data = Socialite::driver('facebook')->user();
    }
}
