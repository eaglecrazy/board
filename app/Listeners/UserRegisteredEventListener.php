<?php

namespace App\Listeners;

use App\Jobs\User\UserRegisteredNotifyJob;

class UserRegisteredEventListener
{
    public function handle($event)
    {
        dd(route('register.verify', '$this->user->verify_token'));
        UserRegisteredNotifyJob::dispatch($event->user);
    }
}
