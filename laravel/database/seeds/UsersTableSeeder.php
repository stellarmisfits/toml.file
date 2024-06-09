<?php

use App\Models\Team;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)
            ->create([
                'email' => 'user@example.com',
                'password' => bcrypt('123')
            ]);

        $team = factory(Team::class)->create([
            'owner_id' => $user->id
        ]);

        $team->users()->attach($user, ['role' => 'OWNER']);
    }
}
