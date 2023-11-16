<?php

namespace App\Events;

use App\Models\Messages;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendChatMessages implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $to;

    /**
     * Create a new event instance.
     */
    public function __construct(Messages $message, int $to)
    {
        $this->message = $message;
        $this->to = $to;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'. $this->to),
        ];
    }

    public function broadcastAs(){
        return 'SendChatMessages';
    }

    public function broadcastWith(){
        return ['message' => $this->message];
    }
}
