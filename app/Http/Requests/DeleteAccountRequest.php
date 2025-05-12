<?php

namespace App\Http\Requests;

class DeleteAccountRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['required', 'current_password:sanctum']
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Введите пароль',
            'password.current_password' => 'Неверный пароль'
        ];
    }
}
