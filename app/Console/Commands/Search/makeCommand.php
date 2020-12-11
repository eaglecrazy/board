<?php

namespace App\Console\Commands\Search;

use Illuminate\Console\Command;

class makeCommand extends Command
{
    protected $signature = 'search:make';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->call('search:init');
        $this->call('search:reindex');
    }
}
