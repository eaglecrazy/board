<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
//        этот сидер лучше запускать консольной командой seed:regions
//        это нужно сделать перед остальным сидингом
//        $this->call(RegionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AdvertCategoriesTableSeeder::class);
        $this->call(AdvertAttributesTableSeeder::class);
        $this->call(AdvertTableSeeder::class);
        $this->call(AdvertsAttributesValuesTableSeeder::class);
        $this->call(AdvertPhotosTableSeeder::class);
        $this->call(BannersTableSeeder::class);
        $this->call(AdvertDialogsTableSeeder::class);
        $this->call(AdvertDialogMessagesTableSeeder::class);
        $this->call(TicketTicketsTableSeeder::class);
        $this->call(TicketStatusesTableSeeder::class);
        $this->call(TicketsMessagesTableSeeder::class);
        $this->call(AdvertFavoritesTableSeeder::class);
        echo 'Performing: search:make' . PHP_EOL;
        Artisan::call('search:make');
        echo 'Done!' . PHP_EOL;
    }
}
