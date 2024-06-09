<?php

namespace Tests\Feature\Controllers\Validator;

use App\Models\Account;
use App\Models\Asset;
use App\Models\User;
use App\Models\Validator;
use App\Repositories\OrganizationRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Http\Resources\Validator as ValidatorResource;
use ZuluCrypto\StellarSdk\Keypair;

class ValidatorControllerTest extends TestCase
{

    /**
     * GET Collection
     */
    public function testValidatorControllerIndex()
    {
        $validator1 = $this->seeder->seedValidator();
        $user = $this->seeder->seedUserWithTeam($validator1->team);

        $this->assertEquals($validator1->team->id, $user->currentTeam()->id);
        $validator2= $this->seeder->seedValidator();

        $this->actingAs($user);

        $this->getJson(route('validators.index'))
            ->assertStatus(200)
            ->assertJsonFragment((new ValidatorResource($validator1))->toArray())
            ->assertJsonMissing([
                'uuid'      => $validator2->uuid
            ]);
    }

    /**
     * POST
     */
    public function testValidatorControllerStore()
    {
        $user = $this->seeder->seedUserWithTeam();
        $this->actingAs($user);

        $account = $this->seeder->seedAccount($user->currentTeam());
        $validator = factory(Validator::class)->make([
            'team_id' => $user->currentTeam()->id,
            'account_uuid' => $account->uuid
        ]);

        $this->assertDatabaseMissing('validators', [
            'team_id'    => $user->currentTeam()->id,
        ]);

        $response = $this->postJson(route('validators.store'), $validator->toArray())
            ->assertStatus(201);

        $this->assertDatabaseHas('validators', [
            'team_id' => $user->currentTeam()->id
        ]);
    }

    /**
     * GET Resource
     */
    public function testAssetControllerShow()
    {
        $validator = $this->seeder->seedValidator();
        $user = $this->seeder->seedUserWithTeam($validator->team);
        $this->actingAs($user);

        $this->getJson(route('validators.show', [
            $validator->uuid
            ]))
            ->assertStatus(200)
            ->assertJsonFragment((new ValidatorResource($validator))->toArray());
    }

    /**
     * PATCH Resource
     */
    public function testAssetControllerUpdate()
    {
        $validator = $this->seeder->seedValidator();
        $user = $this->seeder->seedUserWithTeam($validator->team);
        $this->actingAs($user);

        $newAccount = $this->seeder->seedAccount($validator->team);
        $updatedValues = factory(Validator::class)->make([
            'account_uuid' => $newAccount->uuid,
        ]);

        $this->patchJson(route('validators.update', $validator->uuid), $updatedValues->toArray())
            ->assertStatus(200);

        $this->assertDatabaseHas('validators', [
            'uuid'          => $validator->uuid,
            'name'          => $updatedValues->name,
            'account_id'    => $newAccount->id,
            'alias'         => $updatedValues->alias,
            'host'          => $updatedValues->host,
            'history'       => $updatedValues->history
        ]);
    }

    /**
     * DELETE Resource
     */
    public function testAssetControllerDelete()
    {
        $validator = $this->seeder->seedValidator();
        $org = $this->seeder->seedOrganization($validator->team);
        $user = $this->seeder->seedUserWithTeam($validator->team);
        $this->actingAs($user);

        $or = new OrganizationRepository();
        $or->addValidator($org, $validator);

        $this->deleteJson(route('validators.destroy', [
            $validator->uuid
            ]))
            ->assertStatus(204);
    }
}
