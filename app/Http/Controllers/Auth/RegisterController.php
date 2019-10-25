<?php

namespace App\Http\Controllers\Auth;

use App\Rules\UnregisteredPhone;
use App\Rules\ProcessedOTPAndPhone;
use App\TemporaryUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber;

class RegisterController
{

    public function __invoke(Request $request)
    {
       $data = $request->validate([
           'phone' => ['required', 'phone:NG', new UnregisteredPhone],
           'first_name' => ['required', 'string'],
           'last_name' => ['required', 'string'],
           'email' => ['nullable', 'email', 'unique:users'],
           'otp' => ['required', new ProcessedOTPAndPhone($request)]
       ]);

        DB::beginTransaction();
       try {

           $data['phone'] = $this->formatPhoneNumber($data['phone']);

           $temp_user = TemporaryUser::where('phone', $data['phone'])->first();

           $user = $this->createUser($data, $temp_user);

            $token = $this->createToken($user);

            if (!$token) {
                return response()->json(['message' => 'An error was encountered.'], 501);
            }

            $temp_user->delete();

           DB::commit();

           return response()->json([
               'message' => 'Account has been created.',
               'data' => [
                    'access_token' => $token,
                   'expires_in' => auth()->factory()->getTTL() * 60,
               ]
           ], 201);

       } catch (\Exception $e) {

           DB::rollBack();

           $message = "========== ERROR ON ACCOUNT CREATION ========== \n";
           $message .= $e->getMessage() . "\n" . $e->getTraceAsString();
           Log::critical($message);


           return response()->json(['message' => 'An Error was encountered. Try Again'], 501);
       }
    }

    private function createUser(array $data, $temp_user)
    {
        $data['otp'] = Hash::make($temp_user->otp);
        $data['role'] = 'user';

        return User::create($data);
    }

    private function createToken(User $user)
    {
        return auth()->login($user);
    }

    private function formatPhoneNumber(string $phone)
    {
        // This will format the phone number with a leading +
        $formatted_number = PhoneNumber::make($phone)->ofCountry('NG');

        // remove the +
        return str_replace('+', '', $formatted_number);
    }
}
