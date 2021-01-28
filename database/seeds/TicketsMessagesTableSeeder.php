<?php

use App\Entity\Ticket\Message;
use App\Entity\Ticket\Ticket;
use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class TicketsMessagesTableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        echo 'Tickets messages seeding is begin' . PHP_EOL;
        Message::where('id', '>', '-1')->delete();
        $tickets = Ticket::all();
        $adminId = User::where('name', 'eagle')->first()->id;
        $data = [];

        foreach ($tickets as $ticket) {
            $data[] = [
                    'ticket_id' => $ticket->id,
                    'user_id' => $adminId,
                    'message' => $faker->text(200),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ];
            $data[] = [
                'ticket_id' => $ticket->id,
                'user_id' => $adminId,
                'message' => $faker->text(200),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        echo 'Inserting data to DB' . PHP_EOL;
        Message::insert($data);
        echo 'Tickets messages seeding is end' . PHP_EOL;
}
}
