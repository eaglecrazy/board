<?php

namespace App\Listeners;

use App\Jobs\User\UserRegisteredNotifyJob;

class UserRegisteredEventListener
{
    public function handle($event)
    {
        $url = route('register.verify', $this->user->verify_token);
        dd($url);
        UserRegisteredNotifyJob::dispatch($event->user);
    }
}
