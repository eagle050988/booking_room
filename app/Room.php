<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = [
        'room_name', 'room_capacity', 'photo', 'deleted_at'
    ];

    protected $hidden = [
        'deleted_at', 
    ];
}