<?php


namespace App\Http\Controllers\API;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PaystackController
{
    const BASE_URL = "https://api.paystack.co/transaction/verify/";
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function init(Request $request)
    {
        // User must have an email in DB, as Paystack requires it
        if (empty($this->user->email)) {
            return response()->json(['message' => "Provide an email in your profile, before attempting payment"], 422);
        }


    }

    private function request($method, $uri, $json = [])
    {
        $options['headers']['Authorization'] = 'Bearer ' . config('services.paystack.secret_key');
        if (!empty($json)) {
            $options['headers']['Content-Type'] = 'application/json';
            $options['json'] = json_encode($json);
        }

        $client = new Client(['base_url' => self::BASE_URL]);

        $request = $client->request($method, $uri, $options);

        $response = (string) $request->getBody();

        return $response;
    }
}
