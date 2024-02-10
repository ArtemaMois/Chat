<?php

namespace App\Events;

use App\Http\Resources\MinifiedMessageResource;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $chat;
    protected $message;

    public function __construct(Chat $chat, Message $message)
    {
        $this->message = $message;
        $this->chat = $chat;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chat->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => new MinifiedMessageResource($this->message),
        ];
    }
}
