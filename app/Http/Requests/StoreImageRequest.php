<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'image' => ['required', 'mimes:png,jpg,jpeg', 'max:2000'],
            'is_main' => ['required', 'boolean']
        ];
    }
}
