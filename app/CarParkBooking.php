<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarParkBooking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'check_in',
    	'check_out',
    	'vehicle_no',
    	'amount',
    	'status'
    ];
}
