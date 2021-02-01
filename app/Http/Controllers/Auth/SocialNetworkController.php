<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Usecases\Auth\AuthBySocialNetworkService;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialNetworkController extends Controller
{
    private AuthBySocialNetworkService $authService;

    public function __construct(AuthBySocialNetworkService $service)
    {
        $this->authService = $service;
    }

    public function redirect($driver): RedirectResponse
    {
        return Socialite::driver($driver)->redirect();
    }

    public function callback($driver): RedirectResponse
    {
        $userData = Socialite::driver($driver)->user();
        try {
            if(Auth::check()){//сюда можно перейти по прямой ссылки
dd('Auth::check()');
                $this->authService->attach($driver, $userData);
            } else {
dd('!!!Auth::check()');
                $user = $this->authService->auth($driver, $userData);
                Auth::login($user);
            }

            return redirect()->intended();
        } catch (DomainException $e) {
dd('DomainException $e');
            if(Auth::user())
                return redirect()->home()->with('error', $e->getMessage());
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}
