<?php

namespace App\Listeners;

use App\Entity\Adverts\Advert\Advert;
use App\Notifications\Advert\ModerationPassedNotification;

class AdvertModerationPassedListener
{
    public function handle($event)
    {
        /** @var Advert $advert */
        $advert =  $event->advert;
        $advert->user->notify(new ModerationPassedNotification($advert));

    }
}
