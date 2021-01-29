<?php

namespace App\Listeners;

use App\Entity\Adverts\Advert\Advert;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegionDeleteEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $region = $event->region;
        //айдишники текущего региона и ВСЕХ вложенных на всех уровнях
        $ids = array_merge($region->getAllInnerRegionsId(), [$region->id]);
        Advert::whereIn('region_id', $ids)->update(['region_id' => $region->parent_id]);
    }
}
