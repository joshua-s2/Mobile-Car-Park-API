<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;

class PaystackController
{
    const BASE_URL = "https://api.paystack.co/transaction/verify/";
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function initialize(Request $request)
    {
        // User must have an email in DB, as Paystack requires it
        if (empty($this->user->email)) {
            return response()->json(['message' => "Provide an email in your profile, before attempting payment"], 422);
        }


    }

    private function request()
    {

    }
}
