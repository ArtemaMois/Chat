<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $chat;
    protected $message;
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }


    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat" . $this->chat->id),
        ];
    }


}
