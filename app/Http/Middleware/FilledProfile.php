<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FilledProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if($user->notFilledPhone()){
            return redirect()->route('cabinet.profile.home')
                ->with('error', 'Вам необходимо ввести и подтвердить номер мобильного телефона.');
        }
        if(!$user->isPhoneVerified()){
            return redirect()->route('cabinet.profile.home')
                ->with('error', 'Вам необходимо подтвердить номер мобильного телефона.');
        }
        return $next($request);
    }
}
