<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Регистрация
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'passport_number' => $request->passport_number,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Вы зарегистрировали аккаунт',
            'user' => $user
        ], 201);
    }

    // Вход
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Вы вошли в аккаунт',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // Выход
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Вы вышли из аккаунта'
        ]);
    }

    // Данные пользователя
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    // Удаление аккаунта
    public function destroy(DeleteAccountRequest $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Вы успешно удалили аккаунт'
        ]);
    }
}
