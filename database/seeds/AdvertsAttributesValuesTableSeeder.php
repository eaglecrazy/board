<?php

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\AttributeValue;
use Illuminate\Database\Seeder;
use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;

class AdvertsAttributesValuesTableSeeder extends Seeder
{
    public function run()
    {
        echo 'Adverts attributes values seeding is begin' . PHP_EOL;
        AttributeValue::where('attribute_id', '>', -1)->delete();

        $camerasCategoryId = Category::where('name', 'Фотоаппараты')->first()->id;
        $lensesCategoryId = Category::where('name', 'Объективы')->first()->id;
        $flashesCategoryId = Category::where('name', 'Вспышки')->first()->id;

        $camerasAttributes = Attribute::where('category_id', $camerasCategoryId)->get();
        $lensesAttributes = Attribute::where('category_id', $lensesCategoryId)->get();
        $flashesAttributes = Attribute::where('category_id', $flashesCategoryId)->get();

        $adverts = Advert::all();
        $count = $countAll = $adverts->count();

        $data = [];

        foreach ($adverts as $advert) {
            if ($count-- % 1000 === 0) {
                echo $count + 1 . ' adverts of ' . $countAll . ' left.' . PHP_EOL;
            }

            $categoryId = $advert->category_id;
            $advertId = $advert->id;
            $advertBrand = explode(' ', $advert->title)[0];

            if ($categoryId === $camerasCategoryId) {
                $data = array_merge($data, $this->seedCameras($advertId, $camerasAttributes, $advertBrand));
                continue;
            } else if ($categoryId === $lensesCategoryId) {
                $data = array_merge($data, $this->seedLenses($advertId, $lensesAttributes, $advertBrand));
                continue;
            } else if ($categoryId === $flashesCategoryId) {
                $data = array_merge($data, $this->seedFlashes($advertId, $flashesAttributes, $advertBrand));
                continue;
            }
        }
        echo 'Adverts attributes values seeding is end' . PHP_EOL;
        echo 'Inserting data to DB' . PHP_EOL;
        AttributeValue::insert($data);
        echo 'Performing: search:make' . PHP_EOL;
        Artisan::call('search:make');
        echo 'Done!' . PHP_EOL;

    }

    private function seedCameras(int $advertId, $camerasAttributes, $advertBrand)
    {
        $result = [];
        foreach ($camerasAttributes as $attribute) {

            if ($attribute->name === 'Тип фотоаппарата') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $attribute->variants[rand(0, 3)],
                ];
            } elseif ($attribute->name === 'Бренд') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $advertBrand,
                ];
            } elseif ($attribute->name === 'Гарантия') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => rand(0, 1) ? 'on' : 0,
                ];
            } elseif ($attribute->name === 'Наличие родной упаковки') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => rand(0, 1) ? 'on' : 0,
                ];
            }
        }
        return $result;
    }

    private function seedLenses(int $advertId, $lensesAttributes, $advertBrand)
    {
        $result = [];
        foreach ($lensesAttributes as $attribute) {
            if ($attribute->name === 'Тип объектива') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $attribute->variants[rand(0, 4)],
                ];
            } else if ($attribute->name === 'Бренд') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $advertBrand,
                ];
            } else if ($attribute->name === 'Байонет') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $attribute->variants[rand(0, 2)],
                ];
            } else if ($attribute->name === 'Автофокус') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $attribute->variants[rand(0, 1)],
                ];
            } else if ($attribute->name === 'Стабилизатор') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $attribute->variants[rand(0, 1)],
                ];
            } elseif ($attribute->name === 'Гарантия') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => rand(0, 1) ? 'on' : 0,
                ];
            } elseif ($attribute->name === 'Наличие родной упаковки') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => rand(0, 1) ? 'on' : 0,
                ];
            }
        }
        return $result;
    }

    private function seedFlashes(int $advertId, $flashesAttributes, $advertBrand)
    {
        $result = [];
        foreach ($flashesAttributes as $attribute) {
            if ($attribute->name === 'Тип вспышки') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $attribute->variants[rand(0, 2)],
                ];
            } else if ($attribute->name === 'Бренд') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => $advertBrand,
                ];
            } elseif ($attribute->name === 'Гарантия') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => rand(0, 1) ? 'on' : 0,
                ];
            } elseif ($attribute->name === 'Наличие родной упаковки') {
                $result[] = [
                    'advert_id' => $advertId,
                    'attribute_id' => $attribute->id,
                    'value' => rand(0, 1) ? 'on' : 0,
                ];
            }
        }
        return $result;
    }
}
