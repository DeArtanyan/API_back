<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RateRoomRequest;
use App\Http\Requests\DeleteRatingRequest;

class RoomController extends Controller
{
    public function index(): JsonResponse
    {
        $rooms = Room::all()->map(function ($room) {
            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'room_type' => $room->room_type,
                'price_per_night' => $room->price_per_night,
                'max_guests' => $room->max_guests,
                'description' => $room->description,
                'rating' => $room->rating
            ];
        });

        return response()->json($rooms);
    }

    public function rate($id, RateRoomRequest $request)
    {
        $room = Room::findOrFail($id);

        RoomRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'room_id' => $room->id
            ],
            [
                'rating' => $request->rating
            ]
        );

        $room->update([
            'rating_sum' => $room->rating_sum + $request->rating,
            'rating_count' => $room->rating_count + 1
        ]);

        return response()->json([
            'message' => 'Рейтинг успешно сохранен',
            'rating' => $room->rating
        ]);
    }

    public function deleteRating($roomId): JsonResponse
    {
        $room = Room::findOrFail($roomId);

        $rating = RoomRating::where('room_id', $room->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $ratingValue = $rating->rating;
        $rating->delete();

        $room->update([
            'rating_sum' => $room->rating_sum - $ratingValue,
            'rating_count' => $room->rating_count - 1
        ]);

        return response()->json([
            'message' => 'Оценка удалена',
            'new_rating' => $room->fresh()->rating
        ]);
    }
}
