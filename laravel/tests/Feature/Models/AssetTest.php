<?php

namespace Tests\Feature\Models;

use App\Models\Account;
use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Query\Builder;

class AssetTest extends TestCase
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
    public function testAccountRelationship()
    {
        $account = $this->seeder->seedAccount();
        $asset = $this->seeder->seedAsset($account->team, $account);

        $this->assertEquals($account->id, $asset->account->id);
    }

    /**
     *
     */
    public function testOrganizationRelationship()
    {
        $org = $this->seeder->seedOrganization();
        $account = $this->seeder->seedAccount($org->team);
        $asset = $this->seeder->seedAsset($org->team, $account);

        $this->assertNull($asset->organization);

        (new OrganizationRepository)->addAccount($org, $account);

        $this->assertEquals($org->id, $asset->fresh()->organization->id);
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
    public function testAccountUuidFilter()
    {
        $account = $this->seeder->seedAccount();
        $asset = $this->seeder->seedAsset($account->team, $account);

        $result = $asset::accountUuidFilter($account)->first();
        $this->assertEquals($asset->id, $result->id);
    }

    /**
     *
     */
    public function testOrganizationUuidFilter()
    {
        $team  = $this->seeder->seedTeam();
        $asset1 = $this->seeder->seedAsset($team);
        $asset2= $this->seeder->seedAsset($team);
        $org   = $this->seeder->seedOrganization($team);

        $or = new OrganizationRepository();
        $or->addAccount($org, $asset1->account);

        $results = (new Asset())->linkedOrganizationUuidFilter($org->uuid)->get();
        $this->assertCount(1, $results);
        $this->assertEquals($asset1->id, $results[0]->id);
    }
}
