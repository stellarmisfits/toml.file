<?php

namespace Tests\Feature\Controllers\Organization;

use App\Models\Organization;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\OrganizationRepository;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use ZuluCrypto\StellarSdk\Model\Account as StellarAccount;

class AccountRepositoryTest extends TestCase
{
    public function testAccountRepositoryVerify()
    {
        $team = $this->seeder->seedTeam();
        $org = $this->seeder->seedOrganization($team);
        $account = $this->seeder->seedAccount($team);
        (new OrganizationRepository)->addAccount($org, $account);

        $sAccount = \Mockery::Mock(StellarAccount::class);
        $sAccount->shouldReceive('getHomeDomain')->once()->andReturn($org->url);

        (new AccountRepository())->verifyHomeDomain($account, $sAccount);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'home_domain' => $org->url,
        ]);
    }

    public function testAccountRepositoryVerifyNoOrganization()
    {
        $team = $this->seeder->seedTeam();
        $org = $this->seeder->seedOrganization($team);
        $account = $this->seeder->seedAccount($team);

        $sAccount = \Mockery::Mock(StellarAccount::class);
        $sAccount->shouldReceive('getHomeDomain')->once()->andReturn($org->url);

        $this->expectException(ValidationException::class);
        (new AccountRepository())->verifyHomeDomain($account, $sAccount);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'home_domain' => null,
        ]);
    }

    public function testAccountRepositoryVerifyNoMatch()
    {
        $team = $this->seeder->seedTeam();
        $org = $this->seeder->seedOrganization($team);
        $account = $this->seeder->seedAccount($team);
        (new OrganizationRepository)->addAccount($org, $account);

        $sAccount = \Mockery::Mock(StellarAccount::class);
        $sAccount->shouldReceive('getHomeDomain')->once()->andReturn(null);

        $this->expectException(ValidationException::class);
        (new AccountRepository())->verifyHomeDomain($account, $sAccount);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'home_domain' => null,
        ]);
    }
}
