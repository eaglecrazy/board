<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
//        $this->call(RegionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AdvertCategoriesTableSeeder::class);
        $this->call(AdvertAttributesTableSeeder::class);
        $this->call(AdvertTableSeeder::class);
        $this->call(AdvertsAttributesValuesTableSeeder::class);
        $this->call(AdvertPhotosTableSeeder::class);
        $this->call(BannersTableSeeder::class);
    }
}
