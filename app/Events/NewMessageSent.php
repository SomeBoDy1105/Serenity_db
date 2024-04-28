<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     *
     * @param ChatMessage $chatMessage
     */
    public function __construct(private ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chatMessage->chat_id),
        ];
    }


    /**
     * Broadcast's event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }


    /**
     * data sending back to client.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'chat_id' => $this->chatMessage->chat_id,
            'message' => $this->chatMessage->toArray(),
        ];
    }
}
