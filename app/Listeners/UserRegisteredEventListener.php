<?php

namespace App\Listeners;

use App\Jobs\User\UserRegisteredNotifyJob;

class UserRegisteredEventListener
{
    public function handle($event)
    {
        //почему то в уведомлении и в джобе
        //route('register.verify', $this->user->verify_token) генерирует вместо домена localhost.
        //поэтому передаём урл отсюда
        $url = route('register.verify', $event->user->verify_token);
        dd($url);

        UserRegisteredNotifyJob::dispatch($event->user, $url);
    }
}
