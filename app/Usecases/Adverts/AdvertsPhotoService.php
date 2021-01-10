<?php


namespace App\Usecases\Adverts;


use App\Entity\Adverts\Advert\Photo;

class AdvertsPhotoService
{
    public function getPhotosArray(array $adverts): array
    {
        $photos = [];
        foreach ($adverts as $advert) {
            $photo = Photo::where('advert_id', $advert->id)->first();
            $photos[$advert->id] = $photo->file;
        }
        return $photos;
    }
}
