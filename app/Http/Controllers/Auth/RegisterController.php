<?php

namespace App\Http\Controllers\Auth;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Usecases\Auth\RegisterService;


class RegisterController extends Controller
{
//    use RegistersUsers;

//    protected $redirectTo = '/cabinet';
    private $service;

    public function __construct(RegisterService $service)
    {
        $this->middleware('guest');
        $this->service = $service;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->service->register($request);
        return redirect()->route('login')->with('success', 'Check your email and click on the link to verify.');
    }

    public function verify($token)
    {
        $user = User::where('verify_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Sorry, your link cannot be identified.');
        }

        try {
            $this->service->verify($user->id);
            return redirect()->route('login')->with('success', 'Your email is verified. You can now login.');
        } catch (\DomainException $e){
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}
