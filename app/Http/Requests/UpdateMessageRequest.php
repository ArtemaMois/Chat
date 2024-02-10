<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'message_id' => ['required', 'exists:messages,id'],
            'body' => ['required', 'string']
        ];
    }
}
