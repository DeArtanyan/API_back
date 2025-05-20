<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddServiceRequest;
use App\Http\Requests\RemoveServiceRequest;
use App\Models\Booking;
use App\Models\Service;

class BookingServiceController extends Controller
{
    public function addService(AddServiceRequest $request, $bookingId, $serviceId)
    {
        $booking = Booking::find($bookingId);
        $service = Service::find($serviceId);

        $booking->services()->attach($serviceId, ['is_active' => true]);

        return response()->json([
            'message' => 'Услуга успешно добавлена',
            'service' => $service,
            'booking' => $booking->only(['id', 'room_id'])
        ], 201);
    }

    public function removeService(RemoveServiceRequest $request, $bookingId, $serviceId)
    {
        $booking = Booking::find($bookingId);
        $booking->services()->updateExistingPivot($serviceId, ['is_active' => false]);

        return response()->json([
            'message' => 'Услуга успешно отменена'
        ]);
    }

    public function listServices($bookingId)
    {
        $booking = Booking::with(['services' => function($query) {
            $query->wherePivot('is_active', true);
        }])
            ->where('user_id', auth()->id())
            ->findOrFail($bookingId);

        return response()->json([
            'services' => $booking->services,
            'total_cost' => $booking->services->sum('price')
        ]);
    }
}
