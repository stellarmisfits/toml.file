<?php

use App\Models\Validator;
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

$factory->define(Validator::class, function (Faker $faker) {
    return [
        'name' => substr($faker->country, 0, 49),
        'alias' => substr($faker->slug, 0, 12),
        'host' => $faker->url,
        'history' => $faker->url,
    ];
});
