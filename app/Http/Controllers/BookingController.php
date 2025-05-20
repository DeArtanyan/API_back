<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\CancelBookingRequest;

class BookingController extends Controller
{
    public function store(BookingRequest $request)
    {

        $room = Room::findOrFail($request->room_id);

        $checkInDate = Carbon::parse($request->check_in_date);
        $checkOutDate = Carbon::parse($request->check_out_date);

        $days = $checkInDate->diffInDays($checkOutDate);
        $totalCost = $room->price_per_night * $days;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'guests_count' => $request->guests_count,
            'total_cost' => $totalCost,
            'status' => 'confirmed'
        ]);

        return response()->json([
            'message' => 'Номер успешно забронирован',
            'data' => [
                'booking_id' => $booking->id,
                'room_number' => $room->room_number,
                'check_in' => $checkInDate->format('Y-m-d'),
                'check_out' => $checkOutDate->format('Y-m-d'),
                'total_days' => $days,
                'total_cost' => $totalCost,
                'status' => 'confirmed'
            ]
        ], 201);
    }

    public function index()
    {
        $bookings = Booking::with(['room', 'user'])
            ->where('user_id', auth()->id())
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'room_number' => $booking->room->room_number,
                    'check_in' => Carbon::parse($booking->check_in_date)->format('Y-m-d'),
                    'check_out' => Carbon::parse($booking->check_out_date)->format('Y-m-d'),
                    'guests_count' => $booking->guests_count,
                    'total_cost' => $booking->total_cost,
                    'status' => $booking->status
                ];
            });

        return response()->json($bookings);
    }

    public function destroy(CancelBookingRequest $request, $id)
    {

        $booking = Booking::where('user_id', auth()->id())
            ->findOrFail($id);

        $booking->delete();

        return response()->json([
            'message' => 'Бронирование отменено'
        ]);
    }
}
