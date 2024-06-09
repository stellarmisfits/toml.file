<?php

namespace Tests\Feature\Models;

use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
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
    public function testTeamRelationship()
    {
        $account = $this->seeder->seedAccount();

        $result = $account->team;
        $this->assertEquals($account->team_id, $result->id);
    }

    /**
     *
     */
    public function testOrganizationRelationship()
    {
        $account = $this->seeder->seedAccount();
        $org = $this->seeder->seedOrganization($account->team);

        $or = new OrganizationRepository();
        $or->addAccount($org, $account);

        $this->assertTrue($org->is($account->organization));
    }

    /**
     *
     */
    public function testAssetsRelationship()
    {
        $account = $this->seeder->seedAccount();
        $asset1 = $this->seeder->seedAsset($account->team, $account);
        $asset2 = $this->seeder->seedAsset($account->team, $account);

        $this->assertCount(2, $account->assets);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     *
     */
    public function testLinkedOrganizationUuidFilterScope()
    {
        $team = $this->seeder->seedTeam();
        $org = $this->seeder->seedOrganization($team);

        $account1 = $this->seeder->seedAccount($team);
        $account2 = $this->seeder->seedAccount($team);

        $or = new OrganizationRepository();
        $or->addAccount($org, $account1);

        $results = Account::linkedOrganizationUuidFilter($org->uuid)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($account1->id, $results[0]->id);
    }

    /**
     *
     */
    public function testUnlinkedOrganizationUuidFilterScope()
    {
        $team = $this->seeder->seedTeam();
        $org = $this->seeder->seedOrganization($team);

        $account1 = $this->seeder->seedAccount($team);
        $account2 = $this->seeder->seedAccount($team);

        $this->assertCount(2, Account::unlinkedOrganizationUuidFilter($org->uuid)->get());

        $or = new OrganizationRepository();
        $or->addAccount($org, $account1);

        $results = Account::unlinkedOrganizationUuidFilter($org->uuid)->get();

        $this->assertCount(1, $results);
        $this->assertEquals($account2->id, $results[0]->id);
    }
}
