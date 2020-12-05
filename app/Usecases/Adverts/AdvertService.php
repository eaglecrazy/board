<?php


namespace App\Usecases\Adverts;


use App\Entity\Adverts\Advert\Advert;
use App\Events\AdvertEvent;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use Illuminate\Database\Eloquent\Collection;
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

    public function edit(Advert $advert, EditRequest $request): void
    {
        $advert->update($request->only([
            'title',
            'content',
            'price',
            'address',
        ]));

        if ($advert->isActive()) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_INDEX));
        }
    }

    public function addPhotos(Advert $advert, PhotosRequest $request): void
    {
        DB::transaction(function () use ($request, $advert) {
            foreach ($request['files'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts')
                ]);
            }
            $advert->update();
        });
    }

    public function sendToModeration(Advert $advert): void
    {
        $advert->sendToModeration();
    }

    public function moderate(Advert $advert): void
    {
        $advert->moderate(Carbon::now());
        event(new AdvertEvent($advert, AdvertEvent::ADVERT_INDEX));
    }

    public function reject(Advert $advert, RejectRequest $request): void
    {
        $active = $advert->isActive();
        $advert->reject($request['reason']);
        if ($active) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_REMOVE));
        }
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
            $advert->update();
        });
        if ($advert->isActive()) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_INDEX));
        }
    }

    public function remove(Advert $advert)
    {
        $active = $advert->isActive();
        $advert->delete();
        if ($active) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_REMOVE));
        }
        //тут нужно ещё фоточки удалить
    }

    public function getSimilar(Advert $advert): Collection
    {
        return Advert::where('region_id', $advert->region ? $advert->region->id : null)
            ->where('category_id', $advert->category->id)
            ->where('id', '!=', $advert->id)
            ->get()
            ->shuffle()
            ->take(3);
    }

    public function expire(Advert $advert): void
    {
        $advert->expire();
        event(new AdvertEvent($advert, AdvertEvent::ADVERT_REMOVE));
    }

    public function close(Advert $advert): void
    {
        $advert->close();
        event(new AdvertEvent($advert, AdvertEvent::ADVERT_REMOVE));
    }
}
