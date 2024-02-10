<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinifiedChatResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'chat_id' => $this->id,
            'chat_name' => $this->name,
            'last_message' => $this->messages->last(),
            'username' => $this->users->map(function (User $user) {
                if ($user->id != auth()->user()->id) {
                    return $user;
                }
            }),
        ];
    }
}
