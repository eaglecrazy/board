<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Photo;
use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\AdvertContentEditRequest;
use App\Http\Requests\Adverts\AddPhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use App\Usecases\Adverts\AdvertService;
use DomainException;
use Illuminate\Http\Request;


class AdminAdvertController extends Controller
{
    private $advertService;

    public function __construct(AdvertService $service){
        $this->advertService = $service;
        $this->middleware('can:manage-adverts');
    }

    public function index(Request $request){
        $query = Advert::orderByDesc('updated_at');

        if(!empty($value = $request->get('id'))){
            $query->where('id', $value);
        }

        if(!empty($value = $request->get('title'))){
            $query->where('title', 'like', '%' . $value . '%');
        }

        if(!empty($value = $request->get('user'))){
            $query->where('user_id', $value);
        }

        if(!empty($value = $request->get('region'))){
            $query->where('region_id', $value);
        }

        if(!empty($value = $request->get('category'))){
            $query->where('category_id', $value);
        }

        if(!empty($value = $request->get('status'))){
            $query->where('status_id', $value);
        }

        $adverts = $query->paginate(20);
        $statuses = Advert::statusesList();
        $roles = User::rolesList();

        return view('admin.adverts.adverts.index', compact('adverts', 'statuses', 'roles'));
    }

    //---------------------------
    // Удаление
    //---------------------------

    public function destroy(Advert $advert)
    {
        try {
            $this->advertService->remove($advert);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.adverts.adverts.index')
            ->with('success', 'Объявление удалено.');
    }

    public function destroyPhoto(Advert $advert, Photo $photo){
        try {
            $this->advertService->removePhoto($photo);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Фотография удалена.');
    }

    //---------------------------
    // Изменение статусов
    //---------------------------
    public function moderate(Advert $advert)
    {
        try {
            $this->advertService->moderate($advert);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function reject(RejectRequest $request, Advert $advert)
    {
        try {
            $this->advertService->reject($advert, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function rejectForm(Advert $advert)
    {
        return view('admin.adverts.reject', compact('advert'));
    }

    //---------------------------
    // Редактирование
    //---------------------------

    public function editForm(Advert $advert)
    {
        $editUser = 'admin';
        return view('adverts.edit.advert', compact('advert', 'editUser'));
    }

    public function updateAdvert (AdvertContentEditRequest $request, Advert $advert)
    {
        try {
            $this->advertService->edit($advert, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Объявление успешно отредактировано.');
    }

    public function updateAttrubutes (AttributesRequest $request, Advert $advert)
    {
        try {
            $this->advertService->editAttributes($advert, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Характеристики успешно отредактированы.');
    }

    public function addPhotos(AddPhotosRequest $request, Advert $advert)
    {
        try {
            $this->advertService->addPhotos($advert, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Фотографии успешно добавлены.');
    }
}
