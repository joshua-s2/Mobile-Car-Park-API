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

    /**
     * Add a parking space to the database
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	// Validate requests
    	$this->validate($request, [
            'name'		  => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'],
            'owner'		  => ['required', 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/'],
            'address'	  => 'required|string',
            'tel'		  => 'required|string',
            'fee'		  => 'required|integer|min:0',
            'image_link'  => 'string|nullable',
        ]);

        $park = new CarPark;

        $park->name 	  = $request->name;
        $park->owner 	  = $request->owner;
        $park->address 	  = $request->address;
        $park->tel 		  = $request->tel;
        $park->fee 		  = $request->fee;
        $park->image_link = $request->image_link;

        // Save to db
        if ($park->save()) {
            return response()->json([
                'status'  => true,
                'result'  => $park,
                'message' => 'Car Park was successfully added'
            ], 200);
        }
        else {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred'
            ], 501);
        }
    }
}
