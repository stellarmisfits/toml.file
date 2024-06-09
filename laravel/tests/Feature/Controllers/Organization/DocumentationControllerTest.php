<?php

namespace Tests\Feature\Controllers\Organization;

use App\Models\Account;
use App\Models\Organization;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class DocumentationControllerTest extends TestCase
{

    /**
     * PATCH Resource
     */
    public function testOrganizationControllerUpdate()
    {
        $organization = $this->seeder->seedOrganization();
        $user = $this->seeder->seedUserWithTeam($organization->team);
        $this->actingAs($user);

        $updatedValues = factory(Organization::class)->make();
        $updatedValues->phone = '+1213s5551235';

        $this->patchJson(route('organizations.documentation', $organization->uuid), $updatedValues->toArray())
            ->assertStatus(200);

        $updatedValues->phone = '+12135551235'; // S should be striped out in the organization observer.
        unset($updatedValues['name']); // Removed in controller validation
        unset($updatedValues['alias']); // Removed in controller validation
        $this->assertDatabaseHas('organizations', $updatedValues->toArray());
    }
}
