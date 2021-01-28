<?php

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Dialog\Dialog;
use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;


class AdvertDialogsTableSeeder extends Seeder
{
    public function run()
    {
        echo 'Adverts dialogs seeding is begin' . PHP_EOL;
        Dialog::where('id', '>', -1)->delete();

        $adverts = Advert::all();
        $count = $countall = $adverts->count();
        $userIds = User::all()->pluck('id');

        $data = [];
        foreach ($adverts as $advert) {
            if ($count-- % 1000 === 0) {
                echo $count + 1 . ' adverts of ' . $countall . ' left.' . PHP_EOL;
            }

            if ($advert->isActive() && rand(0,2)) {

                do {
                    $clientId = $userIds->random();
                } while ($clientId === $advert->user->id);

                $data[] = [
                    'advert_id' => $advert->id,
                    'user_id' => $advert->user->id,
                    'client_id' => $clientId,
                    'user_new_messages' => rand(0, 2),
                    'client_new_messages' => rand(0, 2),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        echo 'Adverts dialogs values seeding is end' . PHP_EOL;
        echo 'Inserting data to DB' . PHP_EOL;
        Dialog::insert($data);
        echo 'Done!' . PHP_EOL;
    }
}
