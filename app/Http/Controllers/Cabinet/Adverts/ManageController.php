<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Photo;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Usecases\Adverts\AdvertService;
use App\Usecases\Adverts\AdvertsPhotoService;
use Illuminate\Support\Facades\Gate;

class ManageController extends Controller
{
    private $advertService;
    private $photoService;

    public function __construct(AdvertService $advert, AdvertsPhotoService $photo)
    {
        $this->advertService = $advert;
        $this->photoService = $photo;
        $this->middleware([FilledProfile::class]);
    }

    public function editPhotosForm(Advert $advert)
    {
        $this->checkAccess($advert);
        return view('adverts.edit.photos', compact('advert'));
    }

//    public function updatePhotos(PhotosRequest $request, Advert $advert)
//    {
//        $this->checkAccess($advert);
//        try {
//            $this->advertService->addPhotos($advert, $request);
//        } catch (\DomainException $e) {
//            return back()->with('error', $e->getMessage());
//        }
//
//        return redirect()->route('adverts.show', $advert);
//    }

    public function editForm(Advert $advert)
    {
        $this->checkAccess($advert);
        $pageTitle = 'Редактирование объявления';
        return view('adverts.edit.advert', compact('advert', 'pageTitle'));
    }

    public function attributes(Advert $advert)
    {
        $this->checkAccess($advert);
        return view('adverts.edit.attributes', compact('advert'));
    }

    public function updateAttributes(AttributesRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->editAttributes($advert, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }

    public function destroy(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->remove($advert);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.adverts.index');
    }

    public function destroyPhoto(Advert $advert, Photo $photo){
        $this->checkAccess($advert);
        try {
            $this->photoService->removePhoto($photo);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.adverts.edit', $advert);
    }


    private function checkAccess(Advert $advert): void
    {
        if (!Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }

    public function edit(EditRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->edit($advert, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    public function send(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->sendToModeration($advert);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }

    public function close(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->close($advert);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }
}
