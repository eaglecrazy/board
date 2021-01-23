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

    public function sendToModeration(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->sendToModeration($advert);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back();
    }

    public function close(Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->close($advert);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back();
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

        return redirect()->route('cabinet.adverts.index')->with('success', 'Объявление удалено.');
    }

    public function destroyPhoto(Advert $advert, Photo $photo){
        $this->checkAccess($advert);
        try {
            $this->advertService->removePhoto($photo);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Фотография удалена.');
    }

    //---------------------------
    // Редактирование
    //---------------------------
    public function editForm(Advert $advert)
    {
        $this->checkAccess($advert);
        $editUser = 'user';
        return view('adverts.edit.advert', compact('advert','editUser'));
    }

    public function updateAdvert(AdvertContentEditRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->edit($advert, $request, true);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Объявление успешно отредактировано. Объявление появится в поиске после модерации.');;
    }

    public function updateAttrubutes(AttributesRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->editAttributes($advert, $request, true);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Характеристики успешно отредактированы. Объявление появится в поиске после модерации.');
    }

    public function addPhotos(AddPhotosRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);
        try {
            $this->advertService->addPhotos($advert, $request, true);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Фотографии успешно добавлены. Объявление появится в поиске после модерации.');
    }

}
