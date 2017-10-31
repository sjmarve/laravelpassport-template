<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailOrZAPhone implements Rule
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
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($value, "ZA");
        } catch (\libphonenumber\NumberParseException $e) {
            $phoneNumber = new \libphonenumber\PhoneNumber();
        }
        //either email or phone number
        return filter_var($value, FILTER_VALIDATE_EMAIL) || $phoneUtil->isValidNumber($phoneNumber);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must either be a valid email or a South African phone number';
    }
}
