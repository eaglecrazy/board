<?php

use Illuminate\Database\Seeder;
use App\Entity\Adverts\Category;

class AdvertCategoriesTableSeeder extends Seeder
{
    public function run()
    {
        Category::where('name', '!=', '')->delete();

        Category::create([
            'name' => $name = 'Фотоаппараты',
            'slug' => Str::slug($name),
            'parent_id' => null
        ]);
        Category::create([
            'name' => $name = 'Объективы',
            'slug' => Str::slug($name),
            'parent_id' => null
        ]);
        Category::create([
            'name' => $name = 'Вспышки',
            'slug' => Str::slug($name),
            'parent_id' => null
        ]);
    }
}

