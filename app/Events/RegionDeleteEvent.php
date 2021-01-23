<?php

namespace App\Events;

use App\Entity\Region;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RegionDeleteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $region;

    public function __construct(Region $region)
    {
        $this->region = $region;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
