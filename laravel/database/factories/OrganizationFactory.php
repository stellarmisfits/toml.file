<?php

use Faker\Generator as Faker;
use Spatie\Regex\Regex;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Organization::class, function (Faker $faker) {
    $slug = strtolower(str_random(12));

    $proto = PhoneNumberUtil::getInstance()->getExampleNumber('US');
    $e164 = PhoneNumberUtil::getInstance()->format($proto, PhoneNumberFormat::E164);

    return [
        'name' => trim(substr($faker->name, 0, 20)),
        'alias' => Regex::replace('/[^a-z]/', '', $slug)->result(),

        'address'               => $faker->streetAddress,
        'address_attestation'   => $faker->url,
        'phone'                 => '+1201s5550123', //$e164,
        'phone_attestation'     => $faker->url,
        'keybase'               => $faker->userName,
        'twitter'               => $faker->userName,
        'github'                => $faker->userName,
        'email'                 => $faker->email,
        'licensing_authority'   => $faker->company,
        'license_type'          => $faker->jobTitle,
        'license_number'        => str_random(10),

    ];
});
