<?php

namespace App\Events;

use App\Entity\Adverts\Advert\Advert;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdvertModerationPassedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }
}