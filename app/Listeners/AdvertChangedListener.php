<?php

namespace App\Listeners;

use App\Services\Search\AdvertIndexer;

class AdvertChangedListener
{
    private $advertsIndexer;

    public function __construct(AdvertIndexer $advertsIndexer)
    {
        $this->advertsIndexer = $advertsIndexer;
    }

    public function handle($event)
    {
        $this->advertsIndexer->index($event->advert);
    }
}
