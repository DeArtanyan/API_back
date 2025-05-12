<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class RegisterRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'passport_number' => 'required|string|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'пороли не совподают',
            'email.unique' => 'почта уже зарегистрирована'
        ];
    }
}
