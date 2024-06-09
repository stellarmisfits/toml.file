<?php

namespace Tests\Feature\Controllers\Account;

use App\Models\Account;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Http\Resources\Account as AccountResource;
use ZuluCrypto\StellarSdk\Keypair;

class VerificationControllerTest extends TestCase
{

    /**
     * GET Collection
     */
    public function testAccountControllerIndex()
    {
        $account1 = $this->seeder->seedAccount();
        $user = $this->seeder->seedUserWithTeam($account1->team);
        $org = $this->seeder->seedOrganization($account1->team);

        $this->assertEquals($account1->team->id, $user->currentTeam()->id);
        $account2 = $this->seeder->seedAccount();

        $this->actingAs($user);

        $this->getJson(route('accounts.index', ['uninked_organization_uuid' => $org->uuid]))
            ->assertStatus(200)
            ->assertJsonFragment((new AccountResource($account1))->toArray())
            ->assertJsonMissing([
                'uuid'      => $account2->uuid
            ]);
    }

    /**
     * POST
     */
    public function testAccountControllerStore()
    {
        $user = $this->seeder->seedUserWithTeam();
        $this->actingAs($user);
        $account = factory(Account::class)->make([
            'team_id' => $user->currentTeam()->id,
        ]);

        $this->assertDatabaseMissing('accounts', [
            'team_id' => $user->currentTeam()->id,
        ]);

        $response = $this->postJson(route('accounts.store'), $account->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('accounts', [
            'team_id' => $user->currentTeam()->id
        ]);
    }

    /**
     * GET Resource
     */
    public function testAccountControllerShow()
    {
        $account = $this->seeder->seedAccount();
        $user = $this->seeder->seedUserWithTeam($account->team);
        $this->actingAs($user);

        $this->actingAs($user, 'api')
            ->getJson(route('accounts.show', [
                $account->uuid
            ]))
            ->assertStatus(200)
            ->assertJsonFragment((new AccountResource($account))->toArray());
    }

    /**
     * PATCH Resource
     */
    public function testAccountControllerUpdate()
    {
        $account = $this->seeder->seedAccount();
        $user = $this->seeder->seedUserWithTeam($account->team);
        $this->actingAs($user);

        $updatedValues = factory(Account::class)->make();

        $this->patchJson(route('accounts.update', $account->uuid), $updatedValues->toArray())
            ->assertStatus(200);

        $this->assertDatabaseHas('accounts', [
            'uuid'          => $account->uuid,
            'alias'         => $updatedValues->alias,
            'public_key'    => $updatedValues->public_key
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testAccountControllerDelete()
    {
        $account = $this->seeder->seedAccount();
        $org = $this->seeder->seedOrganization($account->team);
        $user = $this->seeder->seedUserWithTeam($account->team);
        $this->actingAs($user);

        $or = new OrganizationRepository();
        $or->addAccount($org, $account);

        $this->deleteJson(route('accounts.destroy', [
                $account->uuid
            ]))
            ->assertStatus(204);
    }
}
