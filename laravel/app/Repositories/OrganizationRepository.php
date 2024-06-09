<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Organization;
use App\Models\Principal;
use App\Models\User;
use App\Models\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrganizationRepository
{

    /**
     * @param User $user
     * @param array $data
     * @return Organization
     */
    public function create(User $user, array $data): Organization
    {
        $o = new Organization();
        $o->team_id     = $user->currentTeam()->id;
        $o->alias       = str_slug($data['alias']);
        $o->name        = $data['name'];
        $o->published   = false;
        $o->save();

        // dispatch event to check whether the account is verified

        return $o;
    }

    /**
     * @param Organization $org
     * @param array $data
     * @return Organization
     */
    public function update(Organization $org, array $data): Organization
    {
        return \DB::transaction(function () use ($org, $data) {
            if (!empty($data['custom_url'])){
                $data['custom_url'] = $this->normalizeCustomUrl($data['custom_url']);
            }else{
                $data['custom_url'] = null;
            }

            // If the alias or the custom URL have changed
            if ($org->alias !== $data['alias'] || $org->custom_url !== $data['custom_url']) {

                // Unverify any verified accounts
                $org->accounts->each(function ($account) {
                    if ($account->verified) {
                        $account->verified = false;
                        $account->save();
                    }
                });

                // Unpublish the organization
                if ($org->published) {
                    $org->published = false;
                    $org->save();
                }
            }

            $org->update($data);

            return $org;
        });
    }

    protected function normalizeCustomUrl($input): string{
        $host = parse_url($input, PHP_URL_HOST);
        $port = parse_url($input, PHP_URL_PORT);

        if(!$host){
            $host = parse_url($input, PHP_URL_PATH);
        }

        throw_unless($host, ValidationException::withMessages([
            'custom_url' => 'The given url must contain a valid host.'
        ]));

        if(!$port){
            return $host;
        }

        return $host . ':' . $port;
    }


    /**
     * @param Organization $org
     * @param Account $account
     * @throws Throwable
     */
    public function addAccount(Organization $org, Account $account)
    {
        throw_if($org->published && !$account->verified, ValidationException::withMessages([
            'organization_uuid' => 'You cannot link an unverified account to a published organization. Please navigate to the account details page to perform necessary the verification steps before trying again.'
        ]));

        $account->organization()->associate($org);
        $account->save();
    }


    /**
     * @param Organization $org
     * @param Account $account
     */
    public function removeAccount(Organization $org, Account $account)
    {
        \DB::transaction(function () use ($org, $account) {

            // detach any validators tied to the account
            $validators = $account->validators;
            if ($validators){
                $org->validators()->detach($validators->pluck('id')->toArray());
            }

            // detach the account
            $account->organization()->dissociate();
            $account->save();
        });
    }


    /**
     * @param Organization $org
     * @param Principal $account
     */
    public function addPrincipal(Organization $org, Principal $account)
    {
        $org->principals()->syncWithoutDetaching($account);
    }


    /**
     * @param Organization $org
     * @param Validator $validator
     */
    public function addValidator(Organization $org, Validator $validator)
    {
        \DB::transaction(function () use ($org, $validator) {
            // automatically attach the asset's account
            $this->addAccount($org, $validator->account);

            $org->validators()->syncWithoutDetaching($validator);
        });
    }

    /**
     * @param Organization $organization
     * @throws
     */
    public function publish(Organization $organization) {
        $accounts = $organization->accounts;

        throw_unless($accounts->count(), ValidationException::withMessages([
            'organization_uuid' => 'The given organization is not associated with any accounts and therefore cannot be published. You can link existing accounts from the resources tab or navigate to the accounts page to create a new one.'
        ]));

        $accounts->each(function($account) use ($organization){
            throw_unless($account->verified, ValidationException::withMessages([
                'organization_uuid' => 'All associated accounts have not been verified. All accounts tied to this organization must be verified before it can be published. Please navigate to the account details page to perform necessary the verification steps.'
            ]));
        });


        $organization->published = true;
        $organization->save();
    }
}
