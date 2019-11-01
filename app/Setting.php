<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_notifications',
        'push_notifications',
        'location_tracking'
    ];
}
