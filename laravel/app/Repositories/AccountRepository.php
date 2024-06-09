<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Organization;
use App\Models\Principal;
use App\Models\User;
use App\Models\Validator;
use Illuminate\Validation\ValidationException;
use ZuluCrypto\StellarSdk\Model\Account as StellarAccount;

class AccountRepository
{
    /**
     * @param User $user
     * @return Account
     */
    public function create(User $user, $data): Account
    {
        $a = new Account();
        $a->team_id     = $user->currentTeam()->id;
        $a->name        = $data['name'];
        $a->alias       = str_slug($data['alias']);
        $a->public_key  = strtoupper($data['public_key']);
        $a->save();

        // dispatch event to check whether the account is verified

        return $a;
    }

    /**
     * @param Account $account
     * @param array $data
     * @return Account
     */
    public function update(Account $account, $data): Account
    {
       return \DB::transaction(function () use ($account, $data) {

           $newPublicKey = strtoupper($data['public_key']);

           if($account->public_key !== $newPublicKey){
               $account->verified = false;

               $organization = $account->organization;
               if($organization){
                   $organization->published = false;
                   $organization->save();
               }
           }

           $account->name        = $data['name'];
           $account->alias       = str_slug($data['alias']);
           $account->public_key  = strtoupper($data['public_key']);
           $account->save();

            return $account;
        });
    }

    /**
     * @param Account $account
     * @param StellarAccount $sAccount
     * @throws
     */
    public function verifyHomeDomain(Account $account, StellarAccount $sAccount)
    {
        $home_domain = $sAccount->getHomeDomain();

        throw_unless($account->organization, ValidationException::withMessages([
            'account_uuid' => 'This account is not associated with an organization.'
        ]));

        throw_unless($account->organization->url === $home_domain, ValidationException::withMessages([
            'account_uuid' => 'We checked the stellar network and the home domain for the given account does not match the organization\'s url. Use the transaction builder to update the account\'s home domain.'
        ]));

        $account->home_domain = $home_domain;
        $account->home_domain_updated_at = now();
        $account->save();
    }
}
