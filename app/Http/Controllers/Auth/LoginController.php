<?php

namespace App\Http\Controllers\Auth;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Sms\SmsSender;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends Controller
{
    use ThrottlesLogins;

//    protected $redirectTo = '/cabinet';
    private $sms;

    public function __construct(SmsSender $sms)
    {
        $this->middleware('guest')->except('logout');
        $this->sms = $sms;
    }

    public function showLoginForm(Request $request)
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $authentificate = Auth::attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );

        if ($authentificate) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            $user = Auth::user();
            if ($user->status !== User::STATUS_ACTIVE) {
                Auth::logout();
                return back()->with('error', 'Вам необходимо подвердить электронную почту. Пожалуйста, проверьте email.');
            }

            //если есть двухфакторная аутентификация
            if($user->isPhoneAuthEnabled()){
                Auth::logout();
                $token = (string)random_int(10000, 99999);
                $request->session()->put('auth', [
                    'id' => $user->id,
                    'token' => $token,
                    'remember' => $request->filled('remember'),
                ]);
                $this->sms->send($user->phone, ('Ваш код: ' . $token));
                return redirect()->route('login.phone');
            }

            return redirect()->intended();
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('home');
    }

    public function username()
    {
        return 'email';
    }

    public function phone(){
        return view('auth.phone');
    }

    public function verify(Request $request){
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $this->validate($request, [
            'token' => 'required|string'
        ]);

        if(!$session = $request->session()->get('auth')){
            throw new BadRequestHttpException('Неправильный код.');
        }

        $user = User::findOrFail($session['id']);
        if($request['token'] === $session['token']){
            $request->session()->flush();
            $this->clearLoginAttempts($request);
            Auth::login($user, $session['remember']);
            return redirect()->intended(route('cabinet.home'));
        }

        $this->incrementLoginAttempts();
        throw ValidationException::withMessages(['token' => ['Неправильный код.']]);
    }
}
