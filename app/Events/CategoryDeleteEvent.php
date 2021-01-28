<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CategoryDeleteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $categoryIds;
    public $parentId;

    /**
     * Create a new event instance.
     *
     * @param array $categoryIds
     * @param int $parentId
     */
    public function __construct(array $categoryIds, int $parentId)
    {
        $this->categoryIds = $categoryIds;
        $this->parentId = $parentId;
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
