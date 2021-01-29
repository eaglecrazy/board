<?php

namespace App\Listeners;

use App\Jobs\User\UserRegisteredNotifyJob;

class UserRegisteredEventListener
{
    public function handle($event)
    {
        $url = route('register.verify', $event->user->verify_token);
        dd($url);
        UserRegisteredNotifyJob::dispatch($event->user);
    }
}
