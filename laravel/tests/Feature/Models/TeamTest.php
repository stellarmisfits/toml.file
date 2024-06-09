<?php

namespace Tests\Feature\Models;

use App\Models\Organization;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    |
    */

    //


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     *
     */
    public function testUsersRelationship()
    {
        $team = $this->seeder->seedTeam();
        $this->assertCount(1, $team->users);
    }

    /**
     *
     */
    public function testOrganizationsRelationship()
    {
        $team = $this->seeder->seedTeam();
        $org1 = $this->seeder->seedOrganization($team);
        $org2 = $this->seeder->seedOrganization($team);

        $this->assertCount(2, $team->organizations);
    }

    /**
     *
     */
    public function testAccountsRelationship()
    {
        $team = $this->seeder->seedTeam();
        $account1 = $this->seeder->seedAccount($team);
        $account2 = $this->seeder->seedAccount($team);

        $this->assertCount(2, $team->accounts);
    }

    /**
     *
     */
    public function testAssetsRelationship()
    {
        $team = $this->seeder->seedTeam();
        $a1 = $this->seeder->seedAsset($team);
        $a2 = $this->seeder->seedAsset($team);

        $this->assertCount(2, $team->assets);
    }

    /**
     *
     */
    public function testPrincipalsRelationship()
    {
        $team = $this->seeder->seedTeam();
        $p1 = $this->seeder->seedPrincipal($team);
        $p2 = $this->seeder->seedPrincipal($team);

        $this->assertCount(2, $team->principals);
    }

    /**
     *
     */
    public function testValidatorsRelationship()
    {
        $team = $this->seeder->seedTeam();
        $v1 = $this->seeder->seedValidator($team);
        $v2 = $this->seeder->seedValidator($team);

        $this->assertCount(2, $team->validators);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    |
    */
}
