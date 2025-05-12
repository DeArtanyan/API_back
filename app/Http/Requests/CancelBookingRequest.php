<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Carbon\Carbon;
use Dotenv\Validator;

class CancelBookingRequest extends ApiRequest
{
    public function authorize()
    {
        $booking = Booking::where('user_id', $this->user()->id)->find($this->route('booking'));

        if (!$booking) {
            $this->failNotFound('бронирование не найдено');
        }

        return true;
    }

    public function rules()
    {
        return [
            'reason' => 'sometimes|string|max:500'
        ];
    }

    public function withValidator( $validator)
    {
        $validator->after(function ( $validator) {
            $booking = Booking::find($this->route('booking'));

            if ($booking->status === 'cancelled') {
                $this->failConflict('бронирование уже отменено');
            }

            $minHoursBeforeCheckIn = 24;
            if (Carbon::parse($booking->check_in_date)->diffInHours(now()) < $minHoursBeforeCheckIn) {
                $this->failForbidden('отмена возможна не позднее чем за 24 часа до заезда');
            }
        });
    }
}
