<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Asset::class, function (Faker $faker) {
    return [
        'code' => strtoupper(str_random(12)),
        'display_decimals' => 0,
        'name' => trim(substr($faker->company, 0, 20)),
        'description' => $faker->text(),
        'conditions' => $faker->text()
    ];
});
