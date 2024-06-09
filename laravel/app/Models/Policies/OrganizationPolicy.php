<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the organization.
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
     * Determine whether the user can view the organization.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return $organization->team_id === $user->currentTeam()->id;
    }

    /**
     * Determine whether the user can create organizations.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $team = $user->currentTeam();

        // limit to 10 organizations for the public beta
        if($team->organizations()->count() > 10){
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the organization.
     *
     * @param  User $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        return $user->currentTeam()->id === $organization->team_id;
    }

    /**
     * Determine whether the user can delete the organization.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return $user->currentTeam()->id === $organization->team_id;
    }
}
