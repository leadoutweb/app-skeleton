<?php

namespace App\Http\Requests\Authentication\PasswordResetLinks;

use Illuminate\Foundation\Http\FormRequest;

class StorePasswordResetLinkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
