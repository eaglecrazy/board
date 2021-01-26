<?php

use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
use Illuminate\Database\Seeder;

class AdvertAttributesTableSeeder extends Seeder
{
    public function run()
    {
        Attribute::where('name', '!=', '')->delete();
        $this->makeCameras();
        $this->makeLenses();
        $this->makeFlashes();
    }

    private function makeCameras()
    {
        $cameraId = Category::where('name', 'Фотоаппараты')->first()->id;

        Attribute::create([
            'name' => 'Тип фотоаппарата',
            'type' => 'string',
            'category_id' => $cameraId,
            'variants' => ["Беззеркальный", "Зеркальный", "Компактный", "Плёночный"],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Бренд',
            'type' => 'string',
            'category_id' => $cameraId,
            'variants' => ["Canon", "Nikon", "Sony"],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Гарантия',
            'type' => 'bool',
            'category_id' => $cameraId,
            'variants' => [""],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Гарантия',
            'type' => 'bool',
            'category_id' => $cameraId,
            'variants' => [""],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Наличие родной упаковки',
            'type' => 'bool',
            'category_id' => $cameraId,
            'variants' => [""],
            'sort' => 1,
            'required' => true
        ]);
    }

    private function makeLenses()
    {
        $lensId = Category::where('name', 'Объективы')->first()->id;
        Attribute::create([
            'name' => 'Тип объектива',
            'type' => 'string',
            'category_id' => $lensId,
            'variants' => ["Рыбий глаз", "Макрообъектив", "Стандартный объектив", "Телеобъектив", "Широкоугольный объектив"],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Бренд',
            'type' => 'string',
            'category_id' => $lensId,
            'variants' => ["Canon", "Nikon", "Sony", "Sigma"],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Байонет',
            'type' => 'string',
            'category_id' => $lensId,
            'variants' => ["Canon EF", "Canon EF-M", "Canon EF-S", "Nikon F", "Sony E"],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Автофокус',
            'type' => 'string',
            'category_id' => $lensId,
            'variants' => ["да", "нет"],
            'sort' => 1,
            'required' => false
        ]);

        Attribute::create([
            'name' => 'Стабилизатор',
            'type' => 'string',
            'category_id' => $lensId,
            'variants' => ["да", "нет"],
            'sort' => 1,
            'required' => false
        ]);

        Attribute::create([
            'name' => 'Гарантия',
            'type' => 'bool',
            'category_id' => $lensId,
            'variants' => [""],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Наличие родной упаковки',
            'type' => 'bool',
            'category_id' => $lensId,
            'variants' => [""],
            'sort' => 1,
            'required' => true
        ]);
    }

    private function makeFlashes()
    {
        $flashId = Category::where('name', 'Вспышки')->first()->id;

        Attribute::create([
            'name' => 'Тип вспышки',
            'type' => 'string',
            'category_id' => $flashId,
            'variants' => ["Накамерная", "Кольцевая", "Двухламповая"],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Бренд',
            'type' => 'string',
            'category_id' => $flashId,
            'variants' => ["Canon", "Nikon", "Sony", "Yongnuo"],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Гарантия',
            'type' => 'bool',
            'category_id' => $flashId,
            'variants' => [""],
            'sort' => 1,
            'required' => true
        ]);

        Attribute::create([
            'name' => 'Наличие родной упаковки',
            'type' => 'bool',
            'category_id' => $flashId,
            'variants' => [""],
            'sort' => 1,
            'required' => true
        ]);
    }
}
