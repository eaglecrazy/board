<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Photo;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\AdvertContentEditRequest;
use App\Http\Requests\Adverts\AddPhotosRequest;
use App\Usecases\Adverts\AdvertService;
use DomainException;
use Illuminate\Support\Facades\Gate;

class ManageController extends Controller
{
    private $advertService;

    public function __construct(AdvertService $advert)
    {
        $this->advertService = $advert;
        $this->middleware([FilledProfile::class]);
    }

    private function checkAccess(Advert $advert): void
    {
        if (!Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }

    public function send(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->sendToModeration($advert);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }

    public function close(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->close($advert);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return redirect()->route('adverts.show', $advert);
    }

    //---------------------------
    // Удаление
    //---------------------------
    public function destroy(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->remove($advert);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.adverts.index');
    }

    public function destroyPhoto(Advert $advert, Photo $photo){
        $this->checkAccess($advert);
        try {
            $this->advertService->removePhoto($photo);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

//        $user = 'user';
        return redirect()
            ->route('cabinet.adverts.edit', compact('advert'))
//            ->route('cabinet.adverts.edit', compact('advert', 'user'))
            ->with('success', 'Фотография удалена.');
    }

    //---------------------------
    // Редактирование
    //---------------------------
    public function editForm(Advert $advert)
    {
        $this->checkAccess($advert);
        $pageTitle = 'Редактирование объявления';
        $editUser = 'user';
        return view('adverts.edit.advert', compact('advert', 'pageTitle', 'editUser'));
    }

    public function updateAdvert(AdvertContentEditRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->edit($advert, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
//        $user = 'user';
        return redirect()
            ->route('cabinet.adverts.edit', compact('advert'))
//            ->route('cabinet.adverts.edit', compact('advert', 'user'))
            ->with('success', 'Объявление успешно отредактировано.');
    }

    public function updateAttrubutes(AttributesRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->editAttributes($advert, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
//        $user = 'user';
        return redirect()
//            ->route('cabinet.adverts.edit', compact('advert', 'user'))
            ->route('cabinet.adverts.edit', compact('advert'))
            ->with('success', 'Характеристики успешно отредактированы.');
    }


    public function addPhotos(AddPhotosRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->addPhotos($advert, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

//        $user = 'user';
        return redirect()
//            ->route('cabinet.adverts.edit', compact('advert', 'user'))
            ->route('cabinet.adverts.edit', compact('advert'))
            ->with('success', 'Фотографии успешно добавлены.');
    }



//    public function editPhotosForm(Advert $advert)
//    {
//        $this->checkAccess($advert);
//        return view('adverts.edit.photos', compact('advert'));
//    }


//    public function attributes(Advert $advert)
//    {
//        $this->checkAccess($advert);
//        return view('adverts.edit.attributes', compact('advert'));
//    }





}
