<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class clear extends Command
{
    protected $signature = 'clear';


    public function handle()
    {
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        echo "Cache cleared" . PHP_EOL;
        return;
    }
}
