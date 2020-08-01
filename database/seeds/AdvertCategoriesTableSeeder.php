<?php

use Illuminate\Database\Seeder;
use App\Entity\Adverts\Category;

class AdvertCategoriesTableSeeder extends Seeder
{

    public function run()
    {
        factory(Category::class, 10)
            ->create()
            ->each(
                function (Category $category) {
                    $category->children()
                        ->saveMany(factory(Category::class, $this->counts())
                        ->create()
                        ->each(
                            function (Category $category) {
                                $category->children()
                                    ->saveMany(factory(Category::class, $this->counts())
                                    ->create());
                            }
                        ));
                });


    }

    private function counts(){
        $array = [0, random_int(3, 7)];
        return $array[array_rand($array)];
    }
}

