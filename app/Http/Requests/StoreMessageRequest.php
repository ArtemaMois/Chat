<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{


    public function rules(): array
    {
        return [
            'body' => ['required'],
            'chat_id' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
