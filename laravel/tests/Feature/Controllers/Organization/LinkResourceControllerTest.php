<?php

namespace Tests\Feature\Controllers\Organization;

use App\Models\Organization;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class LinkResourceControllerTest extends TestCase
{

    /**
     * POST
     */
    public function testOrganizationControllerStoreAccount()
    {
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);

        $response = $this->postJson('/api/organizations/' . $org->uuid . '/link', [
            'resource_uuid' => $account->uuid,
            'resource_type' => 'account'
        ])
            ->assertStatus(201);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);
    }

    /**
     * POST
     */
    public function testOrganizationControllerStorePrincipal()
    {
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $principal    = $this->seeder->seedPrincipal($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $this->assertDatabaseMissing('organization_principals', [
            'organization_id' => $org->id,
            'principal_id' => $principal->id
        ]);

        $response = $this->postJson('/api/organizations/' . $org->uuid . '/link', [
            'resource_uuid' => $principal->uuid,
            'resource_type' => 'principal'
        ])
            ->assertStatus(201);

        $this->assertDatabaseHas('organization_principals', [
            'organization_id' => $org->id,
            'principal_id' => $principal->id
        ]);
    }

    /**
     * POST
     */
    public function testOrganizationControllerStoreValidator()
    {
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $validator    = $this->seeder->seedValidator($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $this->assertDatabaseMissing('organization_validators', [
            'organization_id' => $org->id,
            'validator_id' => $validator->id
        ]);

        $response = $this->postJson('/api/organizations/' . $org->uuid . '/link', [
            'resource_uuid' => $validator->uuid,
            'resource_type' => 'validator'
        ])
            ->assertStatus(201);

        $this->assertDatabaseHas('organization_validators', [
            'organization_id' => $org->id,
            'validator_id' => $validator->id
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testResourceLinkControllerDeleteAccount()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or->addAccount($org, $account);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);

        $response = $this->deleteJson('/api/organizations/' . $org->uuid . '/link', [
            'resource_uuid' => $account->uuid,
            'resource_type' => 'account'
        ])
            ->assertStatus(204);

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testResourceLinkControllerDeleteAccountWithPrincipalsAndValidators()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $asset      = $this->seeder->seedAsset($team, $account);
        $principal  = $this->seeder->seedPrincipal($team);
        $validator  = $this->seeder->seedValidator($team, $account);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or->addAccount($org, $account);
        $or->addValidator($org, $validator);
        $or->addPrincipal($org, $principal);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);

        $response = $this->deleteJson('/api/organizations/' . $org->uuid . '/link', [
            'resource_uuid' => $account->uuid,
            'resource_type' => 'account'
        ])
            ->assertStatus(204);

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testResourceLinkControllerDeletePrincipal()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $principal    = $this->seeder->seedPrincipal($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or->addPrincipal($org, $principal);

        $this->assertDatabaseHas('organization_principals', [
            'organization_id' => $org->id,
            'principal_id' => $principal->id
        ]);

        $response = $this->deleteJson('/api/organizations/' . $org->uuid . '/link', [
            'resource_uuid' => $principal->uuid,
            'resource_type' => 'principal'
        ])
            ->assertStatus(204);

        $this->assertDatabaseMissing('organization_principals', [
            'organization_id' => $org->id,
            'principal_id' => $principal->id
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testResourceLinkControllerDeleteValidator()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $validator    = $this->seeder->seedValidator($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or->addValidator($org, $validator);

        $this->assertDatabaseHas('organization_validators', [
            'organization_id' => $org->id,
            'validator_id' => $validator->id
        ]);

        $response = $this->deleteJson('/api/organizations/' . $org->uuid . '/link', [
            'resource_uuid' => $validator->uuid,
            'resource_type' => 'validator'
        ])
            ->assertStatus(204);

        $this->assertDatabaseMissing('organization_validators', [
            'organization_id' => $org->id,
            'validator_id' => $validator->id
        ]);
    }
}
