<?php

namespace App\Http\Controllers;

use App\CarPark;
use App\CarParkHistory;
use App\CarParkBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CarParkHistoryController extends Controller
{
    /**
     * Gets authenticated user's data
     *
     * @return App\User
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Gets parking history
     *
     * @param $id - User's id
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id = null)
    {
    	// Get the user requesting for the history
    	$user_id = $id ?? $this->user->id;

    	// Check if the user has any history data
    	$history = CarParkHistory::whereUserId($user_id)->exist();

    	if (!$history) {
			return response()->json(['message' => 'Ho history data for the user found!'], 404);
    	}

    	// User's history data do exist
    	// proceed...
    	$user_histoy = CarParkBooking::join(
    		'car_park_histories', 'car_park_histories.user_id', 'car_park_bookings.user_id'
    	)->join('car_parks', 'car_parks.id', 'car_park_bookings.car_park_id')
    	->where('car_park_bookings.user_id', $user_id)
    	->where('car_park_histories.car_park_booking_id', 'car_park_bookings.id')
    	->get(['car_park_bookings.*']);

    	// Send the history data for consumption
        return response()->json([
        	'status' => true
        	'count'	 => $user_histoy->count(),
        	'data'	 => $user_histoy,
        ], 200);
    }
}
