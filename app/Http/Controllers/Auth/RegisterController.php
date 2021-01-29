<?php

namespace App\Http\Controllers\Auth;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Usecases\Auth\RegisterService;
use Illuminate\Support\Facades\Auth;


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
        return redirect()->route('login')
            ->with('success', 'Подтвердите свою почту кликнув на ссылку в письме, которое мы отправили на указанный email.');
    }

    public function verify($token)
    {
        $user = User::where('verify_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Извините, ссылка не определена.');
        }

        try {
            $this->service->verify($user->id);
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Ваш email подтверждён. Вы вошли на сайт.');
        } catch (\DomainException $e){
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}
