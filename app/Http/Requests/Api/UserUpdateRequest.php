<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'string|required',
            'email' => 'email|unique:users,email|required',
            'password' => [Password::min(8), 'nullable'],
            'address' => 'nullable|string',
        ];
    }
}
