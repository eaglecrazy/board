<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Banner\Banner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerEditRequest;
use App\Http\Requests\Banner\BannerFileRequest;
use App\Usecases\Banners\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CabinetBannerController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function cancel(Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->service->cancelModeration($banner);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function checkAccess(Banner $banner)
    {
        if (!Gate::allows('manage-own-banner', $banner)) {
            abort(403);
        }
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->service->removeByOwner($banner);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.index');
    }

    public function edit(BannerEditRequest $request, Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->service->editByOwner($banner, $request);
        } catch (\DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function editForm(Banner $banner)
    {
        $this->checkAccess($banner);
        if (!$banner->canBeChanged()) {
            return redirect()->route('cabinet.banners.show', $banner)->with('error', 'Баннер недоступен для редактирования.');
        }

        return view('cabinet.banners.edit', compact('banner'));
    }

    public function file(BannerFileRequest $request, Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->service->changeFile($banner, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function fileForm(Banner $banner)
    {
        $this->checkAccess($banner);
        if (!$banner->canBeChanged()) {
            return redirect()->route('cabinet.banners.show', $banner)->with('error', 'Банннер недоступен для редактирования.');
        }
        $formats = Banner::formatsList();
        return view('cabinet.banners.file', compact('banner', 'formats'));
    }

    public function index()
    {
        $banners = Banner::forUser(Auth::user())->orderByDesc('id')->paginate(20);
        return view('cabinet.banners.index', compact('banners'));
    }

//    public function order(Banner $banner)
//    {
//        $this->checkAccess($banner);
//        try {
//            $banner = $this->service->order($banner);
//            $url = $this->robokassa->generateRedirectUrl($banner, $banner->cost, 'banner');
//            return redirect($url);
//        } catch (\DomainException $e) {
//            return back()->with('error', $e->getMessage());
//        }
//
//        return redirect()->route('cabinet.banners.show', $banner);
//    }

    public function send(Banner $banner): RedirectResponse
    {
        $this->checkAccess($banner);
        try {
            $this->service->sendToModeration($banner);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function show(Banner $banner)
    {
        $this->checkAccess($banner);
        return view('cabinet.banners.show', compact('banner'));
    }
}
