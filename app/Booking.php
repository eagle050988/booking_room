<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
        'user_id', 'room_id', 'total_person', 'booking_time', 'noted', 'check_in_time', 'check_out_time','deleted_at'
    ];

    protected $hidden = [
        'deleted_at', 
    ];
}
