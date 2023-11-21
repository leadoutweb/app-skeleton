<?php

namespace App\Http\Requests\Authentication\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:100', 'unique:users'],
            'name' => ['required', 'string', 'max:100'],
        ];
    }
}
