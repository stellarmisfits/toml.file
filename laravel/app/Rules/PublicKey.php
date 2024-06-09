<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Spatie\Regex\Regex;
use ZuluCrypto\StellarSdk\Keypair;

class PublicKey implements Rule
{

    protected $message = 'The given public key is invalid.';

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
        try {
            Keypair::newFromPublicKey($value);
        } catch (\Exception $e) {
            return false;
        }

        if(!Regex::match('/[A-Z0-9]{56}$/', $value)->hasMatch()){
            $this->message = 'The key must be provided in all uppercase letters';
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
