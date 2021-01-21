<?php

use App\Entity\Adverts\Advert\Advert;
use App\Entity\User\User;
use Illuminate\Database\Seeder;

class AdvertFavoritesTableSeeder extends Seeder
{
    public function run()
    {
        echo 'Favorites seeding is begin' . PHP_EOL;
        DB::table('advert_favorites')->truncate();
        $users = User::all();
        $adverts = Advert::all();
        $data = [];
        foreach ($users as $user) {
            if ($user->isActive()) {
                $num = 0;
                foreach ($adverts as $advert) {
                    if ($num > 10) {
                        break;
                    }
                    if (!rand(0, 6)) {
                        $user->addToFavorites($advert->id);
                        $num++;
                    }
                }
            }
        }
        echo 'Favorites seeding is end' . PHP_EOL;
    }
}
