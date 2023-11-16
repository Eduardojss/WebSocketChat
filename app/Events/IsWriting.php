<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IsWriting implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $to;
    public $is_writing;

    /**
     * Create a new event instance.
     */
    public function __construct($to)
    {
        $this->to = $to;
        $this->is_writing = true;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('writingBaloon.'. $this->to)
        ];
    }

    public function broadcastAs(){
        return 'IsWriting';
    }
}
