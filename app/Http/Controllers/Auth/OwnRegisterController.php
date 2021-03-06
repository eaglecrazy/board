<?php

namespace App\Http\Controllers\Auth;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnRegisterController extends Controller
{
    public function form()
    {
        return view('auth.register')->with('success');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:1', 'confirmed'],
        ]);

        if(!true){
            return redirect()->route('form')->exceptInput();
        }


        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

}
