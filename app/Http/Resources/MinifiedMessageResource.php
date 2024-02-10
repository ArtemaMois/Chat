<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinifiedMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message'=> $this->body,
            'id' => $this->id,
            'username' => $this->user->name,
            'userId' => $this->user->id,
            'chat' => 'chat-' . $this->chat->id,
            'created_at' => Carbon::make($this->created_at)->format('H:i'),
            'time' => Carbon::make($this->created_at)->format('j M Y'),
        ];
    }
}
