<?php


namespace App\Usecases\Adverts;


use App\Entity\Adverts\Advert\Photo;
use Storage;

class AdvertsPhotoService
{
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
}
