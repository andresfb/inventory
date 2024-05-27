<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required_if:email,null'
            ],
            'email' => [
                'required_if:name,null',
                'email',
                'max:254',
            ],
        ];
    }
}
