<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\ProcessedOTPAndPhone;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function __invoke(Request $request)
   {
       $data = $request->validate([
           'phone' => ['required', 'exists:users'],
           'otp' => ['required', new ProcessedOTPAndPhone($request)]
       ]);

       $user = User::where('phone', $data['phone'])->first();

       $token = auth()->login($user);

       return response()->json([
           'message' => 'Logon successful.',
           'data' => [
               'access_token' => $token,
               'expires_in' => auth()->factory()->getTTL() * 60
           ]
       ]);
   }
}
