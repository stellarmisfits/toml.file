<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Validator;
use Illuminate\Auth\Access\HandlesAuthorization;

class ValidatorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the validator.
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
     * Determine whether the user can view any models validators.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return (bool) $user->currentTeam();
    }

    /**
     * Determine whether the user can view the validator.
     *
     * @param  User  $user
     * @param  Validator  $validator
     * @return bool
     */
    public function view(User $user, Validator $validator)
    {
        return $validator->team_id === $user->currentTeam()->id;
    }

    /**
     * Determine whether the user can create validators.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user)
    {
        $team = $user->currentTeam();

        // limit to 50 validators for the public beta
        if($team->validators()->count() > 50){
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the validator.
     *
     * @param  User $user
     * @param  Validator  $validator
     * @return bool
     */
    public function update(User $user, Validator $validator)
    {
        return $user->currentTeam()->id === $validator->team_id;
    }

    /**
     * Determine whether the user can delete the validator.
     *
     * @param  User  $user
     * @param  Validator  $validator
     * @return bool
     */
    public function delete(User $user, Validator $validator)
    {
        return $user->currentTeam()->id === $validator->team_id;
    }
}
