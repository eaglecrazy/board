<?php

namespace App\Events;

use App\Entity\Adverts\Advert\Advert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AdvertEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public const ADVERT_INDEX = 'index';
    public const ADVERT_REMOVE = 'remove';

    public $advert;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param Advert $advert
     * @param string $action
     */
    public function __construct(Advert $advert, string $action)
    {
        $this->advert = $advert;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
