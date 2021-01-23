<?php

namespace App\Listeners;

use App\Entity\Adverts\Advert\Advert;
use App\Services\Search\AdvertIndexer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CategoryUpdateEventListener
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
        $adverts = Advert::whereIn('category_id', $event->categoryIds)->get();
        foreach ($adverts as $advert) {
            $this->advertsIndexer->index($advert);
        }
    }
}
