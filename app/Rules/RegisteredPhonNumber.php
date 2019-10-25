<?php

namespace App\Rules;

use App\Classes\Helper;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class RegisteredPhonNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $formatted_number = Helper::formatPhoneNumber($value);
        return User::where('phone', $formatted_number)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not registered.';
    }
}
