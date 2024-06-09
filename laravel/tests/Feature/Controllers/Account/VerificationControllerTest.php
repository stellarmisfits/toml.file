<?php

namespace Tests\Feature\Controllers\Account;

use App\Models\Account;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use App\Services\ChallengeTransactionService;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Http\Resources\Account as AccountResource;
use ZuluCrypto\StellarSdk\Keypair;

class AccountControllerTest extends TestCase
{
    /**
     * GET Resource
     */
    public function testAccountVerificationControllerShow()
    {
        $account = $this->seeder->seedAccount();
        $user = $this->seeder->seedUserWithTeam($account->team);
        $this->actingAs($user);

        $this->actingAs($user, 'api')
            ->getJson(route('accounts.challenge', [
                $account->uuid
            ]))
            ->assertStatus(200)
            ->assertJsonStructure(['transaction', 'network_passphrase']);
    }

    /**
     * POST
     */
    public function testAccountVerificationControllerStore()
    {
        $account = $this->seeder->seedAccount();
        $account->public_key = env('PUBLIC_KEY1');
        $account->save();

        $user = $this->seeder->seedUserWithTeam($account->team);
        $this->actingAs($user);

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
            'verified' => true,
            'verification_tx' => null
        ]);

        $avs = new ChallengeTransactionService();

        // Get the challenge
        $txE = $avs->getChallenge(Keypair::newFromPublicKey($account->public_key));

        // Sign the challenge
        $txE->sign( env('SECRET_KEY1'));

        $response = $this->postJson(route('accounts.verify', $account->uuid), [
            'transaction' => $txE->toBase64()
        ])->assertStatus(200);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'verified' => true,
            'verification_tx' => $txE->toBase64()
        ]);
    }

}
