<?php

namespace App\Listeners;

use App\Jobs\Advert\ModerationPassedNotifyJob;

class AdvertModerationPassedListener
{
    public function handle($event)
    {
        ModerationPassedNotifyJob::dispatch($event->advert);
    }
}
