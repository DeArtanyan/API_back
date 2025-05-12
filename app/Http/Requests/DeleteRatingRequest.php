<?php

namespace App\Http\Requests;

use App\Models\Room;
use App\Models\RoomRating;

class DeleteRatingRequest extends ApiRequest
{
    public function authorize()
    {
        return RoomRating::where('room_id', $this->route('room'))->where('user_id', $this->user()->id)->exists();
    }

    public function rules()
    {
        return [];
    }
}
