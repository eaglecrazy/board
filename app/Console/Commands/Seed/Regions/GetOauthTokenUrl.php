<?php

namespace App\Console\Commands\Seed\Regions;

use Illuminate\Console\Command;

class GetOauthTokenUrl extends Command
{
    protected $signature = 'seed:get-url';

    public function handle()
    {
        $request_params =
            [
                'client_id' => 7703480,
                'redirect_uri' => 'https://oauth.vk.com/blank.html',
                'response_type' => 'token',
                'display' => 'page',
                'scope' => 'offline',
            ];
        $url = 'https://oauth.vk.com/authorize?' . http_build_query($request_params);
        echo $url;
    }
}
