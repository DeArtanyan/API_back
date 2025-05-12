<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'guests_count',
        'total_cost',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime'
    ];

    protected $dates = [
        'check_in_date',
        'check_out_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot('is_active')
            ->wherePivot('is_active', true);
    }
}
