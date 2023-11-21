<?php

namespace App\Http\Requests\Authentication\Profiles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore(auth()->user())],
            'name' => ['required', 'string', 'max:100'],
        ];
    }
}
