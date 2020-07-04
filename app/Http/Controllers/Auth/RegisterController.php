<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\Auth\VerifyMail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
//    use RegistersUsers;

    protected $redirectTo = '/cabinet';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'verify_token' => Str::random(),
            'status' => User::STATUS_WAIT,
        ]);

        Mail::to($user->email)->send(new VerifyMail($user));
        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Check your email and click on the link to verify.');
    }

    public function verify($token){
        $user = User::where('verify_token', $token)->first();

        if(!$user){
            return redirect()->route('login')
                ->with('error', 'Sorry, your link cannot be identified.');
        }

        if($user->status !== User::STATUS_WAIT){
            return redirect()->route('login')
                ->with('error', 'Your email is already verified.');
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Your email is verified. You can now login');

    }
}
