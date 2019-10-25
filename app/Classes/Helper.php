<?php


namespace App\Classes;


use Propaganistas\LaravelPhone\PhoneNumber;

class Helper
{
    public static function formatPhoneNumber(string $phone)
    {
        // This will format the phone number with a leading +
        $formatted_number = PhoneNumber::make($phone)->ofCountry('NG');

        // remove the +
        return str_replace('+', '', $formatted_number);
    }
}
