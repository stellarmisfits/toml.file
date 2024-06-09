<?php

namespace App\Models;

use App\Rules\ValidateUuid;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Validation\ValidationException;

class BaseModel extends Model
{
    use GeneratesUuid;

    // protected $casts = ['uuid' => 'uuid'];
    protected $uuidVersion = 'ordered';
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    |
    */

    //

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    |
    */

    //

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    |
    */

    //

    /**
     * Override the resolveRouteBinding method to validate the parameter is a uuid
     *
     * @param \Illuminate\Database\Eloquent\Model
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws
     */
    public function resolveRouteBinding($value)
    {
        $validator = app('validator')->make(
            ['id' => $value],
            ['id' => [new ValidateUuid]]
        );

        if (! $validator->passes()) {
            throw new ValidationException($validator);
        }

        return parent::resolveRouteBinding($value);
    }
}
