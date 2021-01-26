<?php

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Photo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Entity\Adverts\Category;

class AdvertPhotosTableSeeder extends Seeder
{
    public function run()
    {
        echo 'Adverts photos seeding is begin' . PHP_EOL;
        Photo::where('id', '>', -1)->delete();

        $adverts = Advert::all();
        $count = $countall = $adverts->count();


        $camerasCategoryId = Category::where('name', 'Фотоаппараты')->first()->id;
        $lensesCategoryId = Category::where('name', 'Объективы')->first()->id;
        $flashesCategoryId = Category::where('name', 'Вспышки')->first()->id;

        $cameras = $this->removePublic(Storage::allFiles('./public/adverts/test/camera'));
        $lenses = $this->removePublic(Storage::allFiles('./public/adverts/test/lens'));
        $flashes = $this->removePublic(Storage::allFiles('./public/adverts/test/flash'));
        $camerasCount = count($cameras)-2;
        $lensesCount = count($lenses)-2;
        $flashesCount = count($flashes)-2;

        $data = [];

        foreach ($adverts as $advert) {
            if ($count-- % 1000 === 0) {
                echo $count + 1 . ' adverts of ' . $countall . ' left.' . PHP_EOL;
            }

            $categoryId = $advert->category_id;
            $advertId = $advert->id;

            if ($categoryId === $camerasCategoryId) {
                $data = array_merge($data, $this->fillData($advertId, $camerasCount, $cameras));
                continue;
            } else if ($categoryId === $lensesCategoryId) {
                $data = array_merge($data, $this->fillData($advertId, $lensesCount, $lenses));
                continue;
            } else if ($categoryId === $flashesCategoryId) {
                $data = array_merge($data, $this->fillData($advertId, $flashesCount, $flashes));
                continue;
            }
        }

        echo 'Adverts photos values seeding is end' . PHP_EOL;
        echo 'Inserting data to DB' . PHP_EOL;

        Photo::insert($data);

        echo 'Done!' . PHP_EOL;
    }

    private function fillData(int $advertId, int $count, array $links)
    {
        $photoNum = 1;
        while($photoNum % 2 === 1){
            $photoNum = rand(0, $count);
        };

        return [
            [
                'advert_id' => $advertId,
                'file' => $links[$photoNum]
            ],
            [
                'advert_id' => $advertId,
                'file' => $links[$photoNum+1]
            ]
        ];
    }

    private function removePublic(array $allFiles): array
    {
        foreach($allFiles as &$name){
            $name = Str::replaceFirst('public/', '', $name);
        }
        return $allFiles;
    }
}
