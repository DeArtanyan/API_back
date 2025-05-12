<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelReview extends Model
{
    protected $fillable = ['user_id', 'comment', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
