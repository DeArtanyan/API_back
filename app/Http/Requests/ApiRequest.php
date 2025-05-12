<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'ошибка валидации',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    protected function fail($message, $status = 400)
    {
        throw new HttpResponseException(
            response()->json(['message' => $message], $status)
        );
    }

    protected function failNotFound($message = 'ресурс не найден')
    {
        $this->fail($message, 404);
    }

    protected function failConflict($message = 'конфликт данных')
    {
        $this->fail($message, 409);
    }

    protected function failForbidden($message = 'доступ запрещен')
    {
        throw new HttpResponseException(
            response()->json(['message' => $message], 403)
        );
    }
}
