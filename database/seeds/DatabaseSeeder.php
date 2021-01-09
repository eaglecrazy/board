<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersTableSeeder::class);
//        $this->call(RegionsTableSeeder::class);
        $this->call(AdvertCategoriesTableSeeder::class);
        $this->call(AdvertAttributesTableSeeder::class);
        $this->call(AdvertTableSeeder::class);
        $this->call(AdvertsAttributesValuesTableSeeder::class);
    }
}
