<?php

use App\Entity\Adverts\Advert\Advert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;



class AdvertTableSeeder extends Seeder
{
    public function run()
    {
        echo 'Adverts seeding is begin' . PHP_EOL;
        Advert::where('title', '!=', '')->delete();
        $num = 1000;
        $GLOBALS['advert_seeder'] = $num;
        factory(Advert::class, $num)->create();
        echo 'Adverts seeding is end' . PHP_EOL;
    }
}
