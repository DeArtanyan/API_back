<?php

namespace App\Http\Requests;

use App\Models\Booking;

class RemoveServiceRequest extends ApiRequest
{
    public function authorize()
    {
        $booking = Booking::where('user_id', $this->user()->id)->find($this->route('booking'));

        if (!$booking) {
            $this->failNotFound('бронирование не найдено');
        }

        if (!$booking->services()->where('service_id', $this->route('service'))->wherePivot('is_active', true)->exists()) {
            $this->failNotFound('услуга не найдена в бронировании');
        }
        return true;
    }

    public function rules()
    {
        return [];
    }
}
