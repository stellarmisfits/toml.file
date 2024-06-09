<?php

use App\Models\Team;
use App\Models\Validator;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\User;
use App\Models\Asset;
use App\Models\Principal;
use App\Models\Organization;
use Ramsey\Uuid\Uuid;
use ZuluCrypto\StellarSdk\Keypair;
use Base32\Base32;

class TestCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }


    /*
    |--------------------------------------------------------------------------
    | Simple Seeders
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * @return User
     */
    public function seedUser(): User {
        $user = factory(User::class)->create();
        return $user;
    }

    /**
     * @param Team
     * @return User
     */
    public function seedTeam(User $user = null): Team {
        if(!$user){
            $user = $this->seedUser();
        }

        $team = factory(Team::class)->create([
            'owner_id' => $user->id
        ]);

        $team->users()->attach($user, ['role' => 'OWNER']);
        return $team;
    }

    /**
     * @param Team $team
     * @return Organization
     */
    public function seedOrganization(Team $team = null): Organization {
        if(!$team){
            $team = $this->seedTeam();
        }

        $org = factory(Organization::class)->create([
            'team_id' => $team->id
        ]);

        return $org;
    }

    /**
     * @param Team $team
     * @param Keypair $keypair
     * @return Account
     */
    public function seedAccount(Team $team = null, Keypair $keypair = null): Account {
        if(!$team){
            $team = $this->seedTeam();
        }

        if(!$keypair){
            // $keypair = Keypair::newFromRawSeed(str_random(32));
        }

        $account = factory(Account::class)->create([
            'team_id' => $team->id,
            // 'public_key' => $keypair->getAccountId()
        ]);

        return $account;
    }

    /**
     * @param Team $team
     * @param Account $account
     * @return Asset
     */
    public function seedAsset(Team $team = null, Account $account = null): Asset {
        if(!$team){
            $team = $this->seedTeam();
        }

        if(!$account){
            $account = $this->seedAccount($team);
        }

        $asset = factory(Asset::class)->create([
            'team_id' => $team->id,
            'account_id' => $account->id
        ]);

        return $asset->fresh();
    }

    /**
     * @param Team $team
     * @param Account $account
     * @return Validator
     */
    public function seedValidator(Team $team = null, Account $account = null): Validator {
        if(!$team){
            $team = $this->seedTeam();
        }

        if(!$account){
            $account = $this->seedAccount($team);
        }

        $validator = factory(Validator::class)->create([
            'team_id' => $team->id,
            'account_id' => $account->id
        ]);

        return $validator;
    }

    /**
     * @param Team $team
     * @return Principal
     */
    public function seedPrincipal(Team $team = null): Principal {
        if(!$team){
            $team = $this->seedTeam();
        }

        $principal = factory(Principal::class)->create([
            'team_id' => $team->id
        ]);

        return $principal;
    }

    /*
    |--------------------------------------------------------------------------
    | Compound Seeders
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * @param Team $team
     * @return User
     * @throws
     */
    public function seedUserWithTeam(Team $team = null): User {
        $user = $this->seedUser();

        if(!$team){
            $team = $this->seedTeam($user);
        }else{
            $team->users()->attach($user, ['role' => 'OWNER']);
        }

        return $user->fresh(['teams']);
    }
}
