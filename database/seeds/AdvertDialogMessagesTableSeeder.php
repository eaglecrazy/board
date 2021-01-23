<?php

use App\Entity\Adverts\Advert\Dialog\Dialog;
use App\Entity\Adverts\Advert\Dialog\Message;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class AdvertDialogMessagesTableSeeder extends Seeder
{

    public function run(Faker $faker)
    {
        echo 'Dialogs messages seeding is begin' . PHP_EOL;
        Message::where('id', '>', -1)->delete();

        $dialogs = Dialog::all();

        $data = [];
        foreach ($dialogs as $dialog) {
            $data[] = [
                'user_id' => $dialog->client_id,
                'dialog_id' => $dialog->id,
                'message' => $faker->text(200),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $data[] = [
                'user_id' => $dialog->client_id,
                'dialog_id' => $dialog->id,
                'message' => $faker->text(200),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $data[] = [
                'user_id' => $dialog->user_id,
                'dialog_id' => $dialog->id,
                'message' => $faker->text(200),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $data[] = [
                'user_id' => $dialog->user_id,
                'dialog_id' => $dialog->id,
                'message' => $faker->text(200),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $data[] = [
                'user_id' => $dialog->client_id,
                'dialog_id' => $dialog->id,
                'message' => $faker->text(200),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        echo 'Dialogs messages values seeding is end' . PHP_EOL;
        echo 'Inserting data to DB' . PHP_EOL;
        Message::insert($data);
        echo 'Done!' . PHP_EOL;
    }
}
