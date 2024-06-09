<?php

namespace Tests\Feature\Controllers\Principal;

use App\Models\Principal;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Http\Resources\Principal as PrincipalResource;
use ZuluCrypto\StellarSdk\Keypair;

class PrincipalControllerTest extends TestCase
{

    /**
     * GET Collection
     */
    public function testPrincipalControllerIndex()
    {
        $principal1 = $this->seeder->seedPrincipal();
        $user = $this->seeder->seedUserWithTeam($principal1->team);

        $this->assertEquals($principal1->team->id, $user->currentTeam()->id);
        $principal2= $this->seeder->seedPrincipal();

        $this->actingAs($user);

        $this->getJson(route('principals.index'))
            ->assertStatus(200)
            ->assertJsonFragment((new PrincipalResource($principal1))->toArray())
            ->assertJsonMissing([
                'uuid'      => $principal2->uuid
            ]);
    }

    /**
     * POST
     */
    public function testPrincipalControllerStore()
    {
        $user = $this->seeder->seedUserWithTeam();
        $this->actingAs($user);

        $principal = factory(Principal::class)->make([
            'team_id' => $user->currentTeam()->id,
        ]);

        $this->assertDatabaseMissing('principals', [
            'team_id'    => $user->currentTeam()->id,
        ]);

        $response = $this->postJson(route('principals.store'), $principal->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('principals', [
            'team_id' => $user->currentTeam()->id
        ]);
    }

    /**
     * GET Resource
     */
    public function testPrincipalControllerShow()
    {
        $principal = $this->seeder->seedPrincipal();
        $user = $this->seeder->seedUserWithTeam($principal->team);
        $this->actingAs($user);

        $this->getJson(route('principals.show', [
            $principal->uuid
            ]))
            ->assertStatus(200)
            ->assertJsonFragment((new PrincipalResource($principal))->toArray());
    }

    /**
     * PATCH Resource
     */
    public function testPrincipalControllerUpdate()
    {
        $principal = $this->seeder->seedPrincipal();
        $user = $this->seeder->seedUserWithTeam($principal->team);
        $this->actingAs($user);

        $updatedValues = factory(Principal::class)->make();

        $this->patchJson(route('principals.update', $principal->uuid), $updatedValues->toArray())
            ->assertStatus(200);

        $this->assertDatabaseHas('principals', [
            'uuid' => $principal->uuid,
            'email' => $updatedValues->email,
            'keybase' => $updatedValues->keybase,
            'telegram' => $updatedValues->telegram,
            'twitter' => $updatedValues->twitter,
            'github' => $updatedValues->github,
            'id_photo_hash' => $updatedValues->id_photo_hash,
            'verification_photo_hash' => $updatedValues->verification_photo_hash,
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testAssetControllerDelete()
    {
        $principal = $this->seeder->seedPrincipal();
        $org = $this->seeder->seedOrganization($principal->team);
        $user = $this->seeder->seedUserWithTeam($principal->team);
        $this->actingAs($user);

        $or = new OrganizationRepository();
        $or->addPrincipal($org, $principal);

        $this->deleteJson(route('principals.destroy', [
            $principal->uuid
            ]))
            ->assertStatus(204);
    }
}
