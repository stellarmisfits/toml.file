<?php

namespace Tests\Feature\Controllers\Organization;

use App\Models\Organization;
use App\Http\Resources\Organization as OrganizationResource;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class OrganizationRepositoryTest extends TestCase
{
    public function testOrganizationRepositoryUpdateCustomUrl()
    {
        $or = new OrganizationRepository();
        $team = $this->seeder->seedTeam();
        $org = $this->seeder->seedOrganization($team);

        $data = $org->toArray();
        $data['custom_url'] =  'https://apples.test.com:8000/.well-known';
        $or->update($org, $data);

        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'custom_url' => 'apples.test.com:8000'
        ]);

        $data['custom_url'] =  '127.0.0.1';
        $or->update($org, $data);

        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'custom_url' => '127.0.0.1'
        ]);

        $data['custom_url'] =  '';
        $or->update($org, $data);

        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'custom_url' => null
        ]);

        $data['custom_url'] =  'localhost';
        $or->update($org, $data);

        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'custom_url' => 'localhost'
        ]);

        unset($data['custom_url']);
        $or->update($org, $data);

        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'custom_url' => null
        ]);
    }

    public function testOrganizationRepositoryAddValidator()
    {
        $or = new OrganizationRepository();
        $team = $this->seeder->seedTeam();
        $org = $this->seeder->seedOrganization($team);
        $validator = $this->seeder->seedValidator($team);
        $user = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or->addValidator($org, $validator);

        $this->assertDatabaseHas('organization_validators', [
            'organization_id' => $org->id,
            'validator_id' => $validator->id
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $validator->account->id,
            'organization_id' => $org->id
        ]);
    }

    public function testOrganizationRepositoryRemoveAccountAsset()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $asset      = $this->seeder->seedAsset($team, $account);
        $validator  = $this->seeder->seedValidator($team, $account);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or->addAccount($org, $account);
        $or->addValidator($org, $validator);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);

        $or->removeAccount($org, $account);

        $this->assertDatabaseMissing('organization_validators', [
            'organization_id' => $org->id,
            'validator_id' => $validator->id
        ]);

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
            'organization_id' => $org->id
        ]);
    }

    public function testOrganizationRepositoryPublishNoAccounts()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $this->expectException(ValidationException::class);
        $or->publish($org);
    }

    public function testOrganizationRepositoryPublishAccountsNotValid()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $or->addAccount($org, $account);

        $this->expectException(ValidationException::class);
        $or->publish($org);
    }

    public function testOrganizationRepositoryCantAddInvalidAccountToPublished()
    {
        $or = new OrganizationRepository();
        $team       = $this->seeder->seedTeam();
        $org        = $this->seeder->seedOrganization($team);
        $account    = $this->seeder->seedAccount($team);
        $user       = $this->seeder->seedUserWithTeam($team);
        $this->actingAs($user);

        $org->published = true;
        $org->save();

        $this->expectException(ValidationException::class);
        $or->addAccount($org, $account);
    }
}
