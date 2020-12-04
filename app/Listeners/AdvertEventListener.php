<?php

namespace App\Listeners;

use App\Events\AdvertEvent;
use App\Services\Search\AdvertIndexer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdvertEventListener
{
    private $advertsIndexer;

    /**
     * Create the event listener.
     *
     * @param AdvertIndexer $adverts
     */
    public function __construct(AdvertIndexer $adverts)
    {
        $this->advertsIndexer = $adverts;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $action = $event->action;
        $this->advertsIndexer->$action($event->advert);
    }
}
