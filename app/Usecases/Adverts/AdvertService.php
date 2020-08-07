<?php


namespace App\Usecases\Adverts;


use App\Entity\Adverts\Advert\Advert;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    public function create($user, $category, $region, CreateRequest $request): Advert
    {
        return DB::transaction(function () use ($request, $user, $category, $region) {

            $advert = Advert::make([
                'title' => $request['title'],
                'content' => $request['content'],
                'price' => $request['price'],
                'address' => $request['address'],
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);

            $advert->saveOrFail();
            //создавать атрибуты можно только после сохранения объявления
            foreach ($category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }
            return $advert;
        });
    }

    public function addPhotos(Advert $advert, PhotosRequest $request): void
    {
        DB::transaction(function () use ($request, $advert) {
            foreach ($request['files'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts')
                ]);
            }
        });
    }

    public function sendToModeration(Advert $advert): void
    {
        $advert->sendToModeration();
    }

    public function moderate(Advert $advert): void
    {
        $advert->moderate(Carbon::now());
    }

    public function reject(Advert $advert, RejectRequest $request): void
    {
        $advert->reject($request['reason']);
    }

    public function editAttributes(Advert $advert, AttributesRequest $request): void
    {
        DB::transaction(function () use ($request, $advert) {
            $this->values()->delete();
            foreach ($advert->category()->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }
        });
    }

    public function remove(Advert $advert)
    {
        $advert->delete();
    }
}
