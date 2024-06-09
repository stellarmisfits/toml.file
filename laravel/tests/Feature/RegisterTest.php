<?php

namespace Tests\Feature;

use App\Models\User ;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    /** @test */
    public function canRegister()
    {
        \Config::set('auth.registration_enabled', true);
        $user = factory(User::class)->make();

        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])
        ->assertSuccessful()
        ->assertJsonStructure(['uuid', 'name', 'email']);

        $user = User::where('email', $user->email)->firstOrFail();

        $this->assertDatabaseHas('teams', [
            'owner_id' => $user->id
        ]);
    }

    /** @test */
    public function canNotRegisterWithExistingEmail()
    {
        factory(User::class)->create(['email' => 'test@test.app']);

        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
    }
}
