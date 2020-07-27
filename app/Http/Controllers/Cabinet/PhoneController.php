<?php

namespace App\Http\Controllers\Cabinet;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PhoneController extends Controller
{
    public function request(Request $request)
    {
        $user = Auth::user();
        try {
            $token = $user->requestPhoneVerification(Carbon::now());
        } catch (\DomainException $e) {
            $request->session()->flash('error', $e->getMessage());
        }
        return redirect()->route('cabinet.profile.phone');
    }

    public function form()
    {
        $user = Auth::user();
        return view('cabinet.profile.phone', compact('user'));
    }

    public function verify(Request $request)
    {
        $this->validate($request, ['token' => 'required|string|max:50']);
        $user = Auth::user();
        try {
            $user->verifyPhone($request['token'], Carbon::now());
        } catch (\DomainException $e) {
            $request->session()->flash('error', $e->getMessage());
        }
        return redirect()->route('cabinet.profile.home');
    }
}

