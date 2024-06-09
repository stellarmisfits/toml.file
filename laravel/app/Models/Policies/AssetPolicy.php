<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Asset;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the asset.
     *
     * @param  User  $user
     * @return bool
     */
    public function before($user)
    {
        if ($user->email === 'admin@astrify.com') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models organizations.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return (bool) $user->currentTeam();
    }

    /**
     * Determine whether the user can view the asset.
     *
     * @param  User  $user
     * @param  Asset  $asset
     * @return mixed
     */
    public function view(User $user, Asset $asset)
    {
        return $asset->team_id === $user->currentTeam()->id;
    }

    /**
     * Determine whether the user can create assets.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $team = $user->currentTeam();

        // limit to 200 assets for the public beta
        if($team->assets()->count() > 200){
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the asset.
     *
     * @param  User $user
     * @param  Asset  $asset
     * @return mixed
     */
    public function update(User $user, Asset $asset)
    {
        return $user->currentTeam()->id === $asset->team_id;
    }

    /**
     * Determine whether the user can delete the asset.
     *
     * @param  User  $user
     * @param  Asset  $asset
     * @return mixed
     */
    public function delete(User $user, Asset $asset)
    {
        return $user->currentTeam()->id === $asset->team_id;
    }
}
