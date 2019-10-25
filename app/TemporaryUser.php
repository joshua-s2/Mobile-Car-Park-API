<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemporaryUser extends Model
{
    protected $fillable = ['otp', 'phone'];
}
