<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class LoginRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function authenticate()
    {
        if (!Auth::attempt($this->only('email', 'password'))) {
            abort(422);
        }
    }
}
