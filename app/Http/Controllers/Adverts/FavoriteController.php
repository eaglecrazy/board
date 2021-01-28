<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Usecases\Adverts\FavoriteService;
use DomainException;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    public function add(Advert $advert){
        try {
            $this->service->add(Auth::id(), $advert->id);
        } catch (DomainException $e){
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Объявление добавлено в избранное.');
    }

    public function remove(Advert $advert){
        try {
            $this->service->remove(Auth::id(), $advert->id);
        } catch (DomainException $e){
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Объявление удалено из избранного.');
    }
}
