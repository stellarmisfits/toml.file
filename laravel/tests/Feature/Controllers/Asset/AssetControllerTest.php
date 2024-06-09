<?php

namespace Tests\Feature\Controllers\Account;

use App\Models\Account;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Http\Resources\Asset as AssetResource;
use ZuluCrypto\StellarSdk\Keypair;

class AssetControllerTest extends TestCase
{

    /**
     * GET Collection
     */
    public function testAssetControllerIndex()
    {
        $asset1 = $this->seeder->seedAsset();
        $user = $this->seeder->seedUserWithTeam($asset1->team);

        $this->assertEquals($asset1->team->id, $user->currentTeam()->id);
        $asset2 = $this->seeder->seedAsset();

        $this->actingAs($user);

        $this->getJson(route('assets.index'))
            ->assertStatus(200)
            ->assertJsonFragment((new AssetResource($asset1))->toArray())
            ->assertJsonMissing([
                'uuid'      => $asset2->uuid
            ]);
    }

    /**
     * POST
     */
    public function testAssetControllerStore()
    {
        $user = $this->seeder->seedUserWithTeam();
        $this->actingAs($user);

        $account = $this->seeder->seedAccount($user->currentTeam());
        $asset = factory(Asset::class)->make([
            'team_id' => $user->currentTeam()->id,
            'account_uuid' => $account->uuid
        ]);

        $this->assertDatabaseMissing('assets', [
            'team_id' => $user->currentTeam()->id,
        ]);

        $response = $this->postJson(route('assets.store'), $asset->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('assets', [
            'team_id' => $user->currentTeam()->id
        ]);
    }

    /**
     * GET Resource
     */
    public function testAssetControllerShow()
    {
        $asset = $this->seeder->seedAsset();
        $user = $this->seeder->seedUserWithTeam($asset->team);
        $this->actingAs($user);

        $this->getJson(route('assets.show', [
            $asset->uuid
            ]))
            ->assertStatus(200)
            ->assertJsonFragment((new AssetResource($asset))->toArray());
    }

    /**
     * PATCH Resource
     */
    public function testAssetControllerUpdate()
    {
        $asset = $this->seeder->seedAsset();
        $user = $this->seeder->seedUserWithTeam($asset->team);
        $this->actingAs($user);

        $newAccount = $this->seeder->seedAccount($asset->team);
        $updatedValues = factory(Asset::class)->make([
            'account_uuid' => $newAccount->uuid,
        ]);

        $this->patchJson(route('assets.update', $asset->uuid), $updatedValues->toArray())
            ->assertStatus(200);

        $this->assertDatabaseHas('assets', [
            'uuid'          => $asset->uuid,
            'name'          => $updatedValues->name,
            'code'          => $updatedValues->code,
            'description'   => $updatedValues->description,
            'account_id'    => $newAccount->id,
        ]);
    }
}
