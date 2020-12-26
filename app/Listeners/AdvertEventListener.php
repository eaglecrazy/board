<?php

namespace App\Listeners;

use App\Events\AdvertEvent;
use App\Services\Search\AdvertIndexer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvertEventListener
{
    private $advertsIndexer;

    public function __construct(AdvertIndexer $adverts)
    {
        $this->advertsIndexer = $adverts;
    }

    public function handle($event)
    {
        $action = $event->action;
        $this->advertsIndexer->$action($event->advert);
    }
}
