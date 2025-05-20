<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Room;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $room = Room::find($this->room_id);

        if (! $room) {
            abort(response()->json([
                'message' => 'Комната не найдена.'
            ], 404));
        }

        if (! $room->isAvailable($this->check_in_date, $this->check_out_date)) {
            abort(response()->json([
                'message' => 'Комната недоступна на выбранные даты.'
            ], 422));
        }

        return [
            'room_id' => 'required|integer|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests_count' => 'required|integer|min:1',
        ];
    }
}
