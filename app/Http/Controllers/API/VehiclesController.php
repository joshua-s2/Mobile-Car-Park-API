<?php


namespace App\Http\Controllers\API;


use App\Classes\Helper;
use App\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiclesController
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Get all user's vehicles
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $vehicles = $this->user->vehicles;
        return response()->json(['data' => $vehicles]);
    }

    /**
     * Add a vehicle to user's records
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number' => ['required', 'string', 'unique:vehicles'],
            'make_model' => ['required', 'string', 'min:4'],
        ]);

        // Main ride can be either true/false
        $data['main_ride'] = $request->filled('main_ride');


        try {

            $this->undoOtherMainRides($data['main_ride']);

            $vehicle = $this->user->vehicles()->create($data);
            return response()->json(['message' => 'Vehicle added.', 'data' => $vehicle]);
        } catch (\Exception $e) {
            Helper::logException($e);
            return response()->json(['message' => $e->getMessage()], 501);
        }

    }

    /**
     * Update a vehicle record
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $vehicle = $this->user->vehicles()->findOrFail($id);

        $data = $request->validate([
            'plate_number' => ['required', 'string', Rule::unique('vehicles')->ignore($id)],
            'make_model' => ['required', 'string', 'min:4'],
        ]);

        $data['main_ride'] = $request->filled('main_ride');

        try {
            $this->undoOtherMainRides($data['main_ride']);

            $vehicle->update($data);

            return response()->json(['message' => 'Vehicle info updated', 'data' => $vehicle]);
        } catch (\Exception $e) {
            Helper::logException($e);
            return response()->json(['message' => $e->getMessage()], 501);
        }
    }

    /**
     * Delete a vehicle record
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $vehicle = $this->user->vehicles()->findOrFail($id);

        try {
            $vehicle->delete();

            return response()->json(null, 202);
        } catch (\Exception $e) {
            Helper::logException($e);
            return response()->json(['message' => $e->getMessage()], 501);
        }
    }

    /**
     * Unmark vehicles as main ride
     * @param bool $ride_request
     */
    public function undoOtherMainRides(bool $ride_request)
    {
        if (!$ride_request) {
            return;
        }
        // if this is chosen to be the main ride, then
        // any revert main ride status in any other record
        $other_main_rides = $this->user->where('main_ride', true)->get();

        if ($other_main_rides->isEmpty()) {
            return;
        }

        foreach ($other_main_rides as $ride) {
            $ride->main_ride = false;
            $ride->update();
        }
    }
}
