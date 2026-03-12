<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $message
    ) {}

    /**
     * Each user listens on their own personal channel: notifications.{userId}
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.' . $this->message->receiver_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification.received';
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'text'        => $this->message->text,
            'sender_id'   => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'receiver_id' => $this->message->receiver_id,
            'created_at'  => $this->message->created_at->format('H:i'),
        ];
    }

    /**
     * Use dedicated broadcasting queue
     */
    public function broadcastQueue(): string
    {
        return 'broadcasting';
    }
}
