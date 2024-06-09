<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use nkkollaw\Multitenancy\Validators\Subdomain;
use Spatie\Regex\Regex;

class Alias implements Rule
{
    protected $message = 'The given alias is invalid.';

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
        if(!Regex::match('/^[a-z](-?[a-z])*$/', $value)->hasMatch()){
            $this->message = 'The given alias must contain only lower case letters and dashes. Regex: \'/^[a-z](-?[a-z])*$/\'';
            return false;
        }

        if (Subdomain::isReserved($value)) {
            $this->message = 'The alias has already been taken.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
