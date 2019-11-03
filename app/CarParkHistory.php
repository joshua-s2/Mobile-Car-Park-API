<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarParkHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'count',
    	'date_time',
    	'vehicle_no',
    	'amount'
    ];
}
