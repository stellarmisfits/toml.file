<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Asset;
use App\Models\User;

class AssetRepository
{
    /**
     * @param User $user
     * @return Asset
     */
    public function create(User $user, $data): Asset
    {
        $account = Account::whereUuid($data['account_uuid'])->firstOrFail();

        $a = new Asset();
        $a->team_id     = $user->currentTeam()->id;
        $a->code        = strtoupper($data['code']);
        $a->name        = $data['name'];
        $a->description = $data['description'];
        $a->account_id  = $account->id;
        $a->save();

        return $a;
    }

    public function update(Asset $asset, $data){
        $account = (new Account)->whereUuid($data['account_uuid'])->firstOrFail();
        $asset->account_id = $account->id;

        $asset->code        = strtoupper($data['code']);
        $asset->name        = $data['name'];
        $asset->description = $data['description'];
        $asset->account_id  = $account->id;
        $asset->save();

        return $asset;
    }

    /**
     * @param Asset $asset
     */
    public function deleteMedia(Asset $asset): void
    {
        $asset->clearMediaCollection();
    }
}
