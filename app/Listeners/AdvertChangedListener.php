<?php

namespace App\Listeners;

use App\Jobs\Advert\ReindexAdvertJob;

class AdvertChangedListener
{
    public function handle($event): void
    {
        ReindexAdvertJob::dispatch($event->advert);
    }
}
