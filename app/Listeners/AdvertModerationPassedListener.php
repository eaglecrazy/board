<?php

namespace App\Listeners;

use App\Entity\Adverts\Advert\Advert;
use App\Notifications\Advert\ModerationPassedNotification;
use App\Services\Search\AdvertIndexer;

class AdvertModerationPassedListener
{
    private $advertsIndexer;

    public function __construct(AdvertIndexer $advertsIndexer)
    {
        $this->advertsIndexer = $advertsIndexer;
    }

    public function handle($event)
    {
        /** @var Advert $advert */
        $advert =  $event->advert;
        $this->advertsIndexer->index($advert);
        $advert->user->notify(new ModerationPassedNotification($advert));

    }
}
