<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

class UnregisteredPhone implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $base = PhoneNumber::make($value)->ofCountry('NG');
        $base = str_replace('+', '', $base);
        return User::where('phone', $base)->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The phone number has already been taken.';
    }
}
