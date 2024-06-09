<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAgreementControllerTest extends TestCase
{
    /**
     * POST
     */
    public function testUserAgreementControllerStore()
    {
        $user = $this->seeder->seedUserWithTeam();
        $user->current_agreement = null;
        $user->save();

        $this->actingAs($user);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'current_agreement' => config('app.current_agreement')
        ]);

        $response = $this->postJson(route('agreements.create'), [
            'accepted' => true
        ])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'current_agreement' => config('app.current_agreement')
        ]);

        $this->assertDatabaseHas('user_agreements', [
            'user_id' => $user->id,
            'sha' => config('app.current_agreement')
        ]);
    }

    /**
     * GET
     */
    public function testUserAgreementMiddleware()
    {
        $user = $this->seeder->seedUserWithTeam();
        $user->current_agreement = null;
        $user->save();

        $this->actingAs($user);

        $this->getJson(route('organizations.index'))
            ->assertStatus(403);

        $user->current_agreement = config('app.current_agreement');
        $user->save();

        $this->getJson(route('organizations.index'))
            ->assertSuccessful();
    }
}
