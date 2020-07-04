<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth;

class LoginController extends Controller
{
    use ThrottlesLogins;

    protected $redirectTo = '/cabinet';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if($this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $authentificate
    }


    public function authenticated(Request $request, $user)
    {
        if(!$user->status !== User::STATUS_ACTIVE){
            $this->guard()->logout();
            return back()->with('error', 'You need to confirm your account. Please check your email.');
        }
        return redirect()->intended($this->redirectPath());
    }
}
