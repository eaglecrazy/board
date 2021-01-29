<?php

namespace App\Listeners;

use App\Jobs\Advert\ModerationPassedNotifyJob;

class AdvertModerationPassedListener
{
    public function handle($event)
    {
        $url = route('adverts.show', $event->advert);
        ModerationPassedNotifyJob::dispatch($event->advert, $url);
    }
}
