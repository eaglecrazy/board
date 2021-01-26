<?php

use App\Entity\Ticket\Status;
use App\Entity\Ticket\Ticket;
use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketStatusesTableSeeder extends Seeder
{
    public function run()
    {
        echo 'Tickets statuses seeding is begin' . PHP_EOL;
        Status::where('id', '>', '-1')->delete();
        $tickets = Ticket::all();
        $adminId = User::where('name', 'eagle')->first()->id;
        $data = [];

        foreach ($tickets as $ticket) {
            $statuses = [];
            if ($ticket->status === Status::OPEN) {
                if (!rand(0, 3)) {
                    $statuses = [
                        [
                            'ticket_id' => $ticket->id,
                            'user_id' => $adminId,
                            'status' => Status::OPEN,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'ticket_id' => $ticket->id,
                            'user_id' => $adminId,
                            'status' => Status::APPROVED,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'ticket_id' => $ticket->id,
                            'user_id' => $adminId,
                            'status' => Status::CLOSED,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'ticket_id' => $ticket->id,
                            'user_id' => $adminId,
                            'status' => Status::OPEN,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                    ];
                } else {
                    $statuses = [[
                        'ticket_id' => $ticket->id,
                        'user_id' => $adminId,
                        'status' => Status::OPEN,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]];
                }

            } elseif ($ticket->status === Status::APPROVED) {
                $statuses = [
                    [
                        'ticket_id' => $ticket->id,
                        'user_id' => $adminId,
                        'status' => Status::OPEN,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                    [
                        'ticket_id' => $ticket->id,
                        'user_id' => $adminId,
                        'status' => Status::APPROVED,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                ];
            } //closed
            else {
                $statuses = [
                    [
                        'ticket_id' => $ticket->id,
                        'user_id' => $adminId,
                        'status' => Status::OPEN,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                    [
                        'ticket_id' => $ticket->id,
                        'user_id' => $adminId,
                        'status' => Status::APPROVED,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                    [
                        'ticket_id' => $ticket->id,
                        'user_id' => $adminId,
                        'status' => Status::CLOSED,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                ];
            }
            $data = array_merge($data, $statuses);
        }
        echo 'Inserting data to DB' . PHP_EOL;
        Status::insert($data);
        echo 'Tickets statuses seeding is end' . PHP_EOL;
    }
}
