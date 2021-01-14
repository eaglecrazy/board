<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Banner\Banner;
use App\Entity\User\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerEditRequest;
use App\Http\Requests\Banner\BannerRejectRequest;
use App\Usecases\Banners\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
        $this->middleware('can:manage-banners');
    }

    //---------------------------
    // Отображение
    //---------------------------
    public function index(Request $request)
    {
        $query = Banner::orderByDesc('updated_at');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('user'))) {
            $query->where('user_id', $value);
        }

        if (!empty($value = $request->get('region'))) {
            $query->where('region_id', $value);
        }

        if (!empty($value = $request->get('category'))) {
            $query->where('category_id', $value);
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        $banners = $query->paginate(20);

        $statuses = Banner::statusesList();

        return view('admin.banners.index', compact('banners', 'statuses'));
    }

    public function show(Banner $banner)
    {
        return view('cabinet.banners.show', compact('banner'));
    }


    //---------------------------
    // Редактирование
    //---------------------------
    public function edit(BannerEditRequest $request, Banner $banner): RedirectResponse
    {
        try {
            $this->service->edit($banner, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Баннер отредактирован.');
    }

    public function editForm(Banner $banner)
    {
        $editUser = 'admin';
        return view('cabinet.banners.edit', compact('banner', 'editUser'));
    }


    //---------------------------
    // Статусы
    //---------------------------
    public function destroy(Banner $banner): RedirectResponse
    {
        try {
            $this->service->removeByAdmin($banner);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.index')->with('success', 'Баннер удалён.');
    }

    public function moderate(Banner $banner): RedirectResponse
    {
        try {
            $this->service->moderate($banner);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Баннер прошёл модерацию.');
    }

    public function pay(Banner $banner): RedirectResponse
    {
        try {
            $this->service->pay($banner);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Баннер оплачен. Показы начаты.');
    }

    public function reject(BannerRejectRequest $request, Banner $banner): RedirectResponse
    {
        try {
            $this->service->reject($banner, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner)->with('success', 'Баннер не прошёл модерацию.');;
    }

    public function rejectForm(Banner $banner)
    {
        return view('admin.banners.reject', compact('banner'));
    }
}
