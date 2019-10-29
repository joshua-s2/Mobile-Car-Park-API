<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarPark extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name',
    	'owner',
    	'address',
    	'tel',
    	'fee'
    	'image_link',
    	'status'
    ];
}
