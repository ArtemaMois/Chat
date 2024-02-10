<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountStoreRequest extends FormRequest
{



    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:3']
        ];
    }
}
