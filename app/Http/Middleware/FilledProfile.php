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
        if(empty($user->hasFilledProfile())){
            return redirect()->route('cabinet.profile.home')
                ->with('error', 'Вам необходимо заполнить профиль и подтвердить номер мобильного телефона.');
        }

        return $next($request);
    }
}
