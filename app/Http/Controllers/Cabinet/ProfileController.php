<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileEditReruest;
use App\Usecases\Profile\ProfileService;
use Auth;
use DomainException;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    private ProfileService $editService;

    public function __construct(ProfileService $service)
    {
        $this->editService = $service;
    }

    public function index()
    {
        $user = Auth::user();
        return view('cabinet.profile.home', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('cabinet.profile.edit', compact('user'));
    }

    public function update(ProfileEditReruest $request): RedirectResponse
    {
        try {
            $this->editService->edit(Auth::user(), $request);
        } catch(DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('cabinet.profile.home');
    }
}
