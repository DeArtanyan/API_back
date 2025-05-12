<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Service;

class AddServiceRequest extends ApiRequest
{
    public function authorize()
    {
        $booking = Booking::where('user_id', $this->user()->id)->find($this->route('booking'));

        if (!$booking) {
            $this->failNotFound('у вас нет бронирования с указанным id');
        }

        return true;
    }

    public function rules()
    {
        return [

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Service::find($this->route('service'))) {
                $this->failNotFound('услуга не найдена');
            }

            $booking = Booking::find($this->route('booking'));
            if ($booking && $booking->services()->where('service_id', $this->route('service'))->wherePivot('is_active', true)->exists()) {
                $this->failConflict('эта услуга уже была добавлена');
            }
        });
    }
}
