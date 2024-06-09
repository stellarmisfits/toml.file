<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Principal;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrincipalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the principal.
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
     * Determine whether the user can view any models principals.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return (bool) $user->currentTeam();
    }

    /**
     * Determine whether the user can view the principal.
     *
     * @param  User  $user
     * @param  Principal  $principal
     * @return bool
     */
    public function view(User $user, Principal $principal)
    {
        return $principal->team_id === $user->currentTeam()->id;
    }

    /**
     * Determine whether the user can create principals.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user)
    {
        $team = $user->currentTeam();

        // limit to 50 assets for the public beta
        if($team->principals()->count() > 50){
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the principal.
     *
     * @param  User $user
     * @param  Principal  $principal
     * @return bool
     */
    public function update(User $user, Principal $principal)
    {
        return $user->currentTeam()->id === $principal->team_id;
    }

    /**
     * Determine whether the user can delete the principal.
     *
     * @param  User  $user
     * @param  Principal  $principal
     * @return bool
     */
    public function delete(User $user, Principal $principal)
    {
        return $user->currentTeam()->id === $principal->team_id;
    }
}
