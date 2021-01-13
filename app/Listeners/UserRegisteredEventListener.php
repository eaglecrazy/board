<?php

namespace App\Listeners;

use App\Jobs\Advert\ModerationPassedNotifyJob;
use App\Jobs\User\UserRegisteredNotifyJob;

class UserRegisteredEventListener
{
    public function handle($event)
    {
        UserRegisteredNotifyJob::dispatch($event->user);
    }
}
