<?php

namespace App\Http\Requests;

use App\Models\HotelReview;

class StoreHotelReviewRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|between:1,5'
        ];
    }
}
