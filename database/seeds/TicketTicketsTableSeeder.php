<?php

use App\Entity\Ticket\Status;
use App\Entity\Ticket\Ticket;
use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class TicketTicketsTableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        echo 'Tickets seeding is begin' . PHP_EOL;
        Ticket::where('id', '>', '-1')->delete();
        $users = User::all();
        $data = [];
        foreach ($users as $user) {
            if ($user->isActive()) {
                $data[] = [
                    'user_id' => $user->id,
                    'subject' => $faker->text(50),
                    'content' => $faker->text(200),
                    'status' => array_keys(Status::statusesList())[rand(0,2)],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $data[] = [
                    'user_id' => $user->id,
                    'subject' => $faker->text(50),
                    'content' => $faker->text(200),
                    'status' => array_keys(Status::statusesList())[rand(0,2)],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                $data[] = [
                    'user_id' => $user->id,
                    'subject' => $faker->text(50),
                    'content' => $faker->text(200),
                    'status' => array_keys(Status::statusesList())[rand(0,2)],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }
        echo 'Inserting data to DB' . PHP_EOL;
        Ticket::insert($data);
        echo 'Tickets seeding is end' . PHP_EOL;
    }
}
