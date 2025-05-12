<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BookingRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_id' => [
                'required',
                'exists:rooms,id',
                function ($attribute, $value, $fail) {
                    $room = \App\Models\Room::find($value);
                    if (!$room->isAvailable($this->check_in_date, $this->check_out_date)) {
                        $fail('этот номер уже забронирован на выбранные даты');
                    }
                }
            ],
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests_count' => 'required|integer|min:1'
        ];
    }
}
