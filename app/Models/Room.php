<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'price_per_night',
        'max_guests',
        'description',
        'is_available',
        'rating_sum',
        'rating_count'
    ];
    protected $casts = [
        'is_available' => 'boolean',
        'rating_sum' => 'float',
        'rating_count' => 'integer'
    ];
    public function userRatings()
    {
        return $this->hasMany(RoomRating::class);
    }
    protected function rating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->rating_count > 0
                ? round($this->rating_sum / $this->rating_count, 1)
                : null
        );
    }
    public function rate(int $rating): float
    {
        $this->userRatings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $rating]
        );

        $this->update([
            'rating_sum' => $this->userRatings()->sum('rating'),
            'rating_count' => $this->userRatings()->count()
        ]);

        return $this->rating;
    }

    public function isAvailable($checkIn, $checkOut): bool
    {
        return !$this->bookings()
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<', $checkIn)
                            ->where('check_out_date', '>', $checkOut);
                    });
            })
            ->exists();
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
