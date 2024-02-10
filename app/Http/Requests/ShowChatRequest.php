<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowChatRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_id' => ['required'],
        ];
    }
}
