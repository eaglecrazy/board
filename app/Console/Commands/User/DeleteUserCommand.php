<?php

namespace App\Console\Commands\User;

use App\Entity\User\User;
use Illuminate\Console\Command;

class DeleteUserCommand extends Command
{
    protected $signature = 'delete';

    public function handle()
    {
        User::orderByDesc('id')->first()->delete();
    }
}
