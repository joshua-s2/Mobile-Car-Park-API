<?php


namespace App\Http\Controllers\API\Auth;


use App\Classes\Helper;
use App\Rules\ProcessedOTPAndPhone;
use App\Rules\RegisteredPhonNumber;
use App\Rules\UnregisteredPhone;
use App\TemporaryUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OTPController
{

    public function sendOTP(Request $request)
    {
        if ($request->has('login')) {
            return $this->generateOPPForLogin($request);
        }

        $data = $request->validate([
            'phone' => ['required', 'string', 'min:11', 'phone:NG', new UnregisteredPhone]
        ]);

        DB::beginTransaction();
        try {
            // Format phone number
            $data['phone'] = Helper::formatPhoneNumber($data['phone']);

            $data['otp'] = $this->generateAndSendOTP($data['phone']);

            TemporaryUser::updateOrCreate(['phone' => $data['phone']], ['otp' => $data['otp']]);

            DB::commit();

            return response()->json(['message' => 'An OTP has been snt to your phone number.']);
        } catch (\Exception $e) {
            DB::rollBack();
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


    private function generateOPPForLogin(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', new RegisteredPhonNumber]
        ]);

        $phone = Helper::formatPhoneNumber($request->phone);

        DB::beginTransaction();
        try {
            $otp = $this->generateAndSendOTP($phone);

            // Save the OTP
            TemporaryUser::query()->updateOrCreate(['phone' => $phone], ['otp' => $otp]);

            DB::commit();

            return response()->json(['message' => 'An OTP has been sent to your phone number.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical("========== Error Sending OTP ========== \n" . $e->getMessage() . "\n" . $e->getTraceAsString());

            return response()->json(['message' => "An error was encountered."], 501);
        }
    }

    private function generateAndSendOTP(string $phone)
    {
        try {
           $otp =  random_int(1000, 9999);
        } catch (\Exception $e) {
           $otp = rand(1000, 9999);
        }
        // TODO Send ana SMS to the phone number
        // for now we'll use a static OTP
        $otp = 1234;

        return $otp;
    }
}
