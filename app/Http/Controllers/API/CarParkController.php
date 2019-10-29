<?php

namespace App\Http\Controllers\API;

use Exeception;
use App\CarPark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CarParkController extends Controller
{
    /**
     * @var string $user
     * @access protected
     */
    protected $user;

    /**
     * Gets authenticated user's data
     *
     * @return App\User
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }
}
