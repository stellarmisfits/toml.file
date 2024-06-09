<?php

use App\Models\Principal;
use Faker\Generator as Faker;

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

$factory->define(Principal::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->email,
        'keybase' => substr($faker->slug, 0, 25),
        'telegram' => substr($faker->slug, 0, 25),
        'twitter' => substr($faker->slug, 0, 25),
        'github' => substr($faker->slug, 0, 25),
        'id_photo_hash' => hash('sha256', str_random(15)),
        'verification_photo_hash' => hash('sha256', str_random(15))
    ];
});
