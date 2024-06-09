<?php

namespace Tests\Feature\Controllers\Organization;

use App\Models\Account;
use App\Models\Organization;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{

    /**
     * GET Collection
     */
    public function testOrganizationControllerIndex()
    {
        $org1 = $this->seeder->seedOrganization();
        $user = $this->seeder->seedUserWithTeam($org1->team);

        $this->assertEquals($org1->team->id, $user->currentTeam()->id);
        $org2 = $this->seeder->seedOrganization();

        $this->actingAs($user);

        $this->getJson(route('organizations.index'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'uuid'                => $org1->uuid,
                'name'              => $org1->name,
            ])
            ->assertJsonMissing([
                'uuid'      => $org2->uuid
            ]);
    }

    /**
     * GET Collection
     */
    public function testOrganizationControllerIndexResourceFilter()
    {
        $team = $this->seeder->seedTeam();
        $org1 = $this->seeder->seedOrganization($team);
        $org2 = $this->seeder->seedOrganization($team);
        $user = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $account1 = $this->seeder->seedAccount($team);
        $account2 = $this->seeder->seedAccount($team);

        $or = new OrganizationRepository();
        $or->addAccount($org1, $account1);
        $or->addAccount($org2, $account2);

        $this->getJson(route('organizations.index', [
            'resource_uuid' => $account1->uuid,
            'resource_type' => 'accounts',
            'resource_query' => 'linked'
        ]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'uuid' => $org1->uuid,
            ])
            ->assertJsonMissing([
                'uuid' => $org2->uuid
            ]);


        $this->getJson(route('organizations.index', [
            'resource_uuid' => $account1->uuid,
            'resource_type' => 'accounts',
            'resource_query' => 'unlinked'
        ]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'uuid' => $org2->uuid,
            ])
            ->assertJsonMissing([
                'uuid' => $org1->uuid
            ]);
    }

    /**
     * POST
     */
    public function testOrganizationControllerStore()
    {

        $user = $this->seeder->seedUserWithTeam();
        $this->actingAs($user);
        $org = factory(Organization::class)->make([
            'team_id' => $user->currentTeam()->id,
        ]);

        $this->assertDatabaseMissing('organizations', [
            'team_id' => $user->currentTeam()->id,
        ]);

        $response = $this->postJson(route('organizations.store'), $org->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('organizations', [
            'team_id' => $user->currentTeam()->id
        ]);
    }

    /**
     * GET Collection
     */
    public function testOrganizationControllerShow()
    {
        $org = $this->seeder->seedOrganization();
        $user = $this->seeder->seedUserWithTeam($org->team);
        $this->actingAs($user);

        $this->getJson(route('organizations.show', [
            'uuid' => $org->uuid
        ]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'uuid' => $org->uuid
            ]);
    }

    /**
     * PATCH Resource
     */
    public function testOrganizationControllerUpdate()
    {
        $organization = $this->seeder->seedOrganization();
        $user = $this->seeder->seedUserWithTeam($organization->team);
        $this->actingAs($user);

        $updatedValues = factory(Organization::class)->make();

        $this->patchJson(route('organizations.update', $organization->uuid), $updatedValues->toArray())
            ->assertStatus(200);

        $this->assertDatabaseHas('organizations', [
            'uuid'          => $organization->uuid,
            'name'          => $updatedValues->name,
            'alias'         => $updatedValues->alias
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testOrganizationControllerDelete()
    {
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or = new OrganizationRepository();
        $or->addAccount($org, $account);

        $this->deleteJson(route('organizations.destroy', [
                $org->uuid
            ]))
            ->assertStatus(204);
    }
}
