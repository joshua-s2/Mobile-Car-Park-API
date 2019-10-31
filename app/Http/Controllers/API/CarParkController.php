<?php

namespace App\Http\Controllers\API;

use Exeception;
use App\CarPark;
use App\Classes\Helper;
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

    /**
     * Add a parking space to the database
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        CarPark $park,
        Request $request
    ){
        // Validate posted data
        $this->validate($request, [
            'name'        => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'],
            'owner'       => ['required', 'string',],
            'address'     => ['required', 'string',],
            'phone'       => ['required', 'string', 'min:11', 'phone:NG'],
            'fee'         => ['required', 'integer', 'min:0'],
            'image_link'  => ['string', 'nullable']
        ]);

        $park->name       = $request->name;
        $park->owner      = $request->owner;
        $park->address    = $request->address;
        $park->phone      = $request->phone;
        $park->fee        = $request->fee;
        $park->image_link = $request->image_link;

        // Save to db
        if ($park->save()) {
            return response()->json([
                'status'  => true,
                'result'  => $park,
                'message' => 'Car update was successfully added'
            ], 200);
        }
        else {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred'
            ], 501);
        }
    }

   /**
     * Update a parking space record
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        CarPark $update,
        Request $request
    ){
        // Get the intended resource
        $update = $update->find($id);

        // Proceed to update if record exists
        if (!is_null($update)) {
            // Validate posted data
            $this->validate($request, [
                'name'       => ['regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'],
                'owner'      => 'string',
                'address'    => 'string',
                'phone'      => ['string', 'min:11', 'phone:NG'],
                'fee'        => ['integer', 'min:0'],
                'image_link' => ['string', 'nullable'],
                'status'     => ['integet',],
            ]);

            $update->name       = $request->name ?? $update->name;
            $update->owner      = $request->owner ?? $update->owner;
            $update->address    = $request->address ?? $update->address;
            $update->phone      = $request->tel ?? $update->phone;
            $update->fee        = $request->fee ?? $update->fee;
            $update->image_link = $request->image_link ?? $update->image_link;
            $update->status     = $request->status ?? $update->status;

            // Save to db
            if ($update->save()) {
                return response()->json([
                    'status'  => true,
                    'result'  => $update,
                    'message' => 'The record was successfully updated'
                ], 200);
            }
            else {
                return response()->json([
                    'status'  => false,
                    'message' => 'An error occurred'
                ], 501);
            }
        }
        else {
            return response()->json([
                'message' => 'The record was not found!'
            ], 404);
        }
    }

   /**
     * Get all parking spaces
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get number of parking spaces to be fetched
        $per_page = $request->query('per_page') ?? 100;

        // return the requested number of parking spaces.
        $parking_spaces = CarPark::paginate($per_page);

        // send response with the parking spaces details
        return response()->json([
            'status' => true,
            'spaces' => $parking_spaces
        ], 200);
    }
}
