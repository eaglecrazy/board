<?php
namespace App\Usecases\Adverts;


use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Photo;
use App\Events\AdvertEvent;
use App\Events\AdvertModerationPassedEvent;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\AdvertContentEditRequest;
use App\Http\Requests\Adverts\AddPhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use DomainException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                    $advert->attributesValues()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }
            //создавать фоточки можно только после сохранения объявления
            $this->addPhotos($advert, $request);
            return $advert;
        });
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

    //---------------------------
    // Изменение статусов
    //---------------------------
    public function close(Advert $advert): void
    {
        if($advert->status !== Advert::STATUS_ACTIVE){
            throw new DomainException('Advert status is not active.');
        }

        $advert->close();
        event(new AdvertEvent($advert, AdvertEvent::ADVERT_REMOVE));
    }

    public function expire(Advert $advert): void
    {
        $advert->expire();
        event(new AdvertEvent($advert, AdvertEvent::ADVERT_REMOVE));
    }

    public function moderate(Advert $advert): void
    {
        $advert->moderate(Carbon::now());
        event(new AdvertModerationPassedEvent($advert));
    }

    public function sendToModeration(Advert $advert): void
    {
        $advert->sendToModeration();
    }

    public function reject(Advert $advert, RejectRequest $request): void
    {
        $active = $advert->isActive();
        $advert->reject($request['reason']);
        if ($active) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_REMOVE));
        }
    }

    //---------------------------
    // Работа с фотографиями
    //---------------------------
    public function getPhotosArray(array $adverts): array
    {
        $photos = [];
        foreach ($adverts as $advert) {
            $id = is_array($advert) ? $advert['id'] : $advert->id;
            $photo = Photo::where('advert_id', $id)->first();
            $photos[$id] = $photo->file;
        }
        return $photos;
    }

    public function removePhoto(Photo $photo)
    {
        $file = $photo->file;
        Photo::where('id', $photo->id)->delete();
        Storage::disk('public')->delete($file);
    }

    public function addPhotos(Advert $advert, Request $request, bool $addAsUser = true): void
    {
        $againModeration = false;
        DB::transaction(function () use ($request, $advert, &$againModeration) {
            foreach ($request['files'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts', 'public')
                ]);
            }

            if($advert->isActive()){
                $advert->status = Advert::STATUS_DRAFT;
                $advert->update();
                $againModeration = true;
            }
        });
        if ($againModeration) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_INDEX));
        }
    }

    //---------------------------
    // Разное
    //---------------------------
    public function getSimilar(Advert $advert): Collection
    {
        return Advert::where('region_id', $advert->region ? $advert->region->id : null)
            ->where('category_id', $advert->category->id)
            ->where('id', '!=', $advert->id)
            ->get()
            ->shuffle()
            ->take(3);
    }

    //---------------------------
    // Редактирование
    //---------------------------
    public function edit(Advert $advert, AdvertContentEditRequest $request, bool $editAsUser = true): void
    {
        $data = $request->only([
            'title',
            'content',
            'price',
            'address',
        ]);

        $againModeration = false;
        if($advert->isActive()){
            $advert->status = Advert::STATUS_DRAFT;
            $againModeration = true;
        }

        $advert->update($data);

        if ($againModeration) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_INDEX));
        }
    }

    public function editAttributes(Advert $advert, AttributesRequest $request, bool $editAsUser = true): void
    {
        $againModeration = false;
        DB::transaction(function () use ($request, $advert, &$againModeration) {
            $advert->attributesValues()->delete();
            foreach ($advert->category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->attributesValues()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }

            if($advert->isActive()){
                $advert->status = Advert::STATUS_DRAFT;
                $againModeration = true;
            }

            $advert->update();
        });

        if ($againModeration) {
            event(new AdvertEvent($advert, AdvertEvent::ADVERT_INDEX));
        }
    }
}
