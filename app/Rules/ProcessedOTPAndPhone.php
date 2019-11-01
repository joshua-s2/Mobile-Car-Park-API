<?php

namespace App\Rules;

use App\OTP;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;

class ProcessedOTPAndPhone implements Rule
{
    private $phone;

    /**
     * Create a new rule instance.
     *
     * @param Request $request
     * @param string $field_name
     */
    public function __construct(Request $request, string $field_name = 'phone')
    {
        $this->phone = $request->input($field_name);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $phone = $this->formantNumber($this->phone);
        return OTP::where('phone', $phone)->where('otp', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The OTP is invalid.';
    }

    protected function formantNumber($number)
    {
        $formatted_number = PhoneNumber::make($number)->ofCountry('NG');
        return str_replace('+', '', $formatted_number);
    }
}
