<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use App\Usecases\Adverts\AdvertService;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    private $service;
    public function __construct(AdvertService $service){
        $this->service = $service;
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

    public function photosForm(Advert $advert)
    {
        return view('adverts.edit.photos', compact('advert'));
    }

    public function updatePhotos(PhotosRequest $request, Advert $advert)
    {
        try {
            $this->service->addPhotos($advert, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function editForm(Advert $advert)
    {
        return view('adverts.edit.advert', compact('advert'));
    }

    public function attributesForm(Advert $advert)
    {
        return view('adverts.edit.attributes', compact('advert'));
    }

    public function updateAttributes(AttributesRequest $request, Advert $advert)
    {
        try {
            $this->service->editAttributes($advert, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }

    public function destroy(Advert $advert)
    {
        try {
            $this->service->remove($advert);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.adverts.adverts.index');
    }

    public function moderate(Advert $advert)
    {
        try {
            $this->service->moderate($advert);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function edit(EditRequest $request, Advert $advert)
    {
        try {
            $this->service->edit($advert, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }

    public function rejectForm(Advert $advert)
    {
        return view('admin.adverts.reject', compact('advert'));
    }

    public function reject(RejectRequest $request, Advert $advert)
    {
        try {
            $this->service->reject($advert, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }


}
