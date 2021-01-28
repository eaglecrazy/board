<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\PhoneVerifyRequest;
use App\Usecases\Profile\PhoneService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PhoneController extends Controller
{
    private PhoneService $phoneService;

    public function __construct(PhoneService $sms)
    {
        $this->phoneService = $sms;
    }

    public function sendVerifyToken(): RedirectResponse
    {
        try {
            $this->phoneService->sendPhoneVerifyToken(Auth::user());
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.profile.phone');
    }

    public function form()
    {
        $user = Auth::user();
        return view('cabinet.profile.phone', compact('user'));
    }

    public function verify(PhoneVerifyRequest $request): RedirectResponse
    {
        try {
            $this->phoneService->checkVerifyToken(Auth::user(), $request['token']);
        } catch (DomainException $e) {
            return redirect()->route('cabinet.profile.home')->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.profile.home')->with('success', 'Телефон успешно верифицирован.');
    }

    public function phoneAuth(): RedirectResponse
    {
        $this->phoneService->togglePhoneAuth(Auth::user());
        return redirect()->route('cabinet.profile.home');
    }
}


