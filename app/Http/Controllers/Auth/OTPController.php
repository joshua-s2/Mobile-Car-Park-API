<?php


namespace App\Http\Controllers\Auth;


use App\Rules\ProcessedOTPAndPhone;
use App\TemporaryUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber;

class OTPController
{

    public function getOTP(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'min:11', 'phone:NG']
        ]);


        try {
            $data['otp'] = random_int(1000, 9999);
        } catch (\Exception $e) {
            $data['otp'] = rand(1000, 9999);
        }
        // TODO Send ana SMS to the phone number
        // for now we'll use a static OTP
        $data['otp'] = 1234;

        try {
            // Format phone number
            $data['phone'] = $this->formatPhoneNumber($data['phone']);

            TemporaryUser::updateOrCreate(['phone' => $data['phone']], ['otp' => $data['otp']]);

            return response()->json(['message' => 'An OTP has been snt to your phone number.']);
        } catch (\Exception $e) {
            Log::critical("========== ERROR SENDING OTP ========== \n" . $e->getMessage() . "\n" . $e->getTraceAsString());

            return response()->json(['message' => 'An error was encountered. Try again'], 501);
        }


    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'phone:NG'],
            'otp' => ['required', new ProcessedOTPAndPhone($request)],
        ]);

        // ProcessedOTPAndPhone class verifies that the OTP is valid
        // a validation error will be thrown otherwise

        return response()->json(['message' => 'OTP verified.']);
    }

    private function formatPhoneNumber(string $phone)
    {
        // This will format the phone number with a leading +
        $formatted_number = PhoneNumber::make($phone)->ofCountry('NG');

        // remove the +
        return str_replace('+', '', $formatted_number);
    }
}
