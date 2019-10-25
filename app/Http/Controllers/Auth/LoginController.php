<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Rules\ProcessedOTPAndPhone;
use App\Rules\RegisteredPhonNumber;
use App\TemporaryUser;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function __invoke(Request $request)
   {
       $data = $request->validate([
           'phone' => ['required', new RegisteredPhonNumber],
           'otp' => ['required', new ProcessedOTPAndPhone($request)]
       ]);

       $data['phone'] = Helper::formatPhoneNumber($data['phone']);

       $user = User::where('phone', $data['phone'])->first();

       $token = auth()->login($user);

       if (! $token) {
           return response()->json(['message' => 'An error ws encountered.'], 500);
       }

       $temp_user = TemporaryUser::where('phone', $data['phone'])->first();

       $temp_user->delete();

       return response()->json([
           'message' => 'Login successful.',
           'data' => [
               'access_token' => $token,
               'expires_in' => auth()->factory()->getTTL() * 60
           ]
       ]);
   }
}
