<?php

namespace Tests\Feature\Controllers\Account;

use App\Models\Account;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\Fixtures\StellarHelpers;
use Tests\TestCase;
use App\Http\Resources\Account as AccountResource;
use ZuluCrypto\StellarSdk\Keypair;
use App\Services\StellarAccountService;
use ZuluCrypto\StellarSdk\Model\Account as StellarAccount;

class DomainControllerTest extends TestCase
{

    /**
     * POST
     */
    public function testDomainControllerStore()
    {
        $account = $this->seeder->seedAccount();
        $org = $this->seeder->seedOrganization($account->team);
        (new OrganizationRepository)->addAccount($org, $account);

        $user = $this->seeder->seedUserWithTeam($account->team);
        $this->actingAs($user);

        $stellarAccountMock = $this->mock(StellarAccount::class, function ($mock) use ($org) {
            $mock->shouldReceive('getHomeDomain')->once()->andReturn($org->url);
        });

        $this->mock(StellarAccountService::class, function ($mock) use ($stellarAccountMock) {
            $mock->shouldReceive('getAccount')->once()->andReturn($stellarAccountMock);
        });

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
            'home_domain' => $org->url
        ]);

        $this->postJson(route('accounts.domain', $account->uuid))
            ->assertSuccessful()
            ->assertJsonFragment([
                'home_domain' => $org->url
            ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'home_domain' => $org->url
        ]);
    }

    /**
     * @group integration
     */
//    public function testVerificationControllerStoreIntegration() {
//        $account = $this->seeder->seedAccount();
//        $org = $this->seeder->seedOrganization($account->team);
//        $user = $this->seeder->seedUserWithTeam($account->team);
//        $this->actingAs($user);
//
//        $sh = new StellarHelpers();
//        $sAccount = $sh->getAccount1();
//        $account->public_key = $sAccount->getPublicKey();
//        $account->save();
//
//        $sh->setHomeDomain($sAccount, $org->url);
//
//        (new OrganizationRepository)->addAccount($org, $account);
//
//        $this->postJson(route('accounts.verify', $account->uuid))
//            ->assertSuccessful();
//    }

}
