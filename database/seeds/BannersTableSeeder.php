<?php

use App\Entity\Banner\Banner;
use App\Entity\User\User;
use Illuminate\Database\Seeder;
use App\Entity\Adverts\Category;

class BannersTableSeeder extends Seeder
{
    public function run()
    {
        echo 'Banners seeding is begin.' . PHP_EOL;
        Banner::where('id', '>', -1)->delete();

        $users = User::all();
        $usersCount = $users->count();
        $bannersCount = $usersCount * 10;
        $banners = factory(Banner::class, $bannersCount)->make();
        echo 'Banners factory done all banners.' . PHP_EOL;

        $userNumber = 0;

        echo 'Filling DB.' . PHP_EOL;

        for ($i = 0; $i < $bannersCount; $i++) {
            $banners[$i]->user_id = $users[$userNumber]->id;
            if ($i > 0 && $i % 10 === 0) {
                $userNumber++;
            }
            $banners[$i]->save();
        }
        echo 'Performing: search:make' . PHP_EOL;
        Artisan::call('search:make');
        echo 'Done!' . PHP_EOL;
    }

}
