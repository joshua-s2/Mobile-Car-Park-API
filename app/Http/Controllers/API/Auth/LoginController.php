<?php

namespace App\Http\Controllers\API\Auth;

use App\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Rules\ProcessedOTPAndPhone;
use App\Rules\RegisteredPhonNumber;
use App\OTP;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Login a client/user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function user(Request $request)
   {
       $data = $request->validate([
           'phone' => ['required', new RegisteredPhonNumber],
           'otp' => ['required', new ProcessedOTPAndPhone($request)]
       ]);

       $data['phone'] = Helper::formatPhoneNumber($data['phone']);

       $user = User::where('phone', $data['phone'])->where('role', 'user')->first();

       $token = auth()->login($user);

       if (! $token) {
           return response()->json(['message' => 'An error ws encountered.'], 500);
       }

       $temp_user = OTP::where('phone', $data['phone'])->first();

       $temp_user->delete();

        return $this->createResponse($token);
   }

    /**
     * Login admin and partners
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function adminAndPartner(Request $request)
   {
       $data = $request->validate([
          'email' => ['required', 'exists:users'],
          'password' => ['required'],
       ]);

       $user = User::query()->where('email', $data['email'])->where('role' , 'admin')
           ->orWhere('role', 'partner')->first();

       if (!$user) {
            return response()->json(['Unauthorized'], 401);
       }

       if (! $token = auth()->login($user)) {
           return response()->json(['message' => 'An error was encountered.'], 500);
       }

       return $this->createResponse($token);
   }

    /**
     * Create a JSON response for successful login
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
   private function createResponse(string $token)
   {
       return response()->json([
           'message' => 'Login successful.',
           'data' => [
               'access_token' => $token,
               'expires_in' => auth()->factory()->getTTL() * 60
           ]
       ]);
   }
}
