<?php

namespace App\Http\Controllers;

use Exception;
use App\CarPark;
use App\CarParkBooking;
use App\CarParkHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CarParkBookingController extends Controller
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
     * Method to schedule a booking
     *
     * @param $id - The car park identifier
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {

    	// Get the parking space
		$parking_space = CarPark::find($id);

		// Check if such a parking space exist
		if (!$parking_space) {
			return response()->json(['message' => 'Parking Space Not Found!'], 404);
		}

		// Validate posted request
		$this->validate($request, [
            'check_in'	  => ['required', 'date'],
            'check_out'	  => ['required', 'date'],
            'vehicle_no'  => ['required', 'string'],
            'amount'	  => ['required', 'integer'],
        ]);

        DB::beginTransaction();

        try {
			// Save the record of the booking against the user
        	$booking = new CarParkBooking;

        	$booking->park_id 		= $id;
        	$booking->user_id 		= $this->user->id;
        	$booking->check_in 		= $request->check_in;
        	$booking->check_out 	= $request->check_out;
        	$booking->vehicle_no 	= $request->vehicle_no;
        	$booking->amount 		= $request->amount;
        	$booking->status 		= 1;

        	$booking->save();

	        // Write the booking transaction to history
        	$history = new CarParkHistory;

        	$history->booking_id 	= $booking->id;
        	$history->user_id 		= $booking->user_id;
        	$history->date_time 	= $request->date_time;
        	$history->vehicle_no 	= $request->vehicle_no;
        	$history->amount 		= $request->amount;

            // transaction was successful
            DB::commit();

            // send response
            return response()->json([
                'status'  => true,
                'message' => 'Car Park has been booked successfully',
                'result'  => $booking
            ], 200);
        } catch(Exception $e) {
            // transaction was not successful
            DB::rollBack();

            return response()->json([
                'status'  => false,
	            'message' => 'Unable to book parking space'
                'hint'    => $e->getMessage()
            ], 501);
		}
    }
}
