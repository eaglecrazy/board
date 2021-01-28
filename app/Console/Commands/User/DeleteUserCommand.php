<?php

namespace App\Console\Commands\User;

use App\Entity\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeleteUserCommand extends Command
{
    protected $signature = 'delete';

    public function handle()
    {
        User::orderByDesc('id')->first()->delete();
        echo "User deleted" . PHP_EOL;
        Artisan::call('clear');
    }
}
