<?php

namespace App\Http\Requests;

class DeleteHotelReviewRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->review->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [];
    }
}
