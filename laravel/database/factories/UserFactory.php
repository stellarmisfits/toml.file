<?php

use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Str;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'current_agreement' => config('app.current_agreement')
    ];
});

$factory->state(User::class, 'withTeam', [])
    ->afterCreatingState(User::class, 'withTeam', function ($user, $faker) {

        $team = factory(Team::class)
            ->create([
                'owner_id' => $user->id
            ]);

        $team->users()->attach($user, ['role' => 'OWNER']);
    });
