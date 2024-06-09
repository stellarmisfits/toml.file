<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the account.
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
     * Determine whether the user can view the account.
     *
     * @param  User  $user
     * @param  Account  $account
     * @return mixed
     */
    public function view(User $user, Account $account)
    {
        return $account->team_id === $user->currentTeam()->id;
    }

    /**
     * Determine whether the user can create accounts.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $team = $user->currentTeam();

        // limit to 50 accounts for the public beta
        if($team->accounts()->count() > 50){
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the account.
     *
     * @param  User $user
     * @param  Account  $account
     * @return mixed
     */
    public function update(User $user, Account $account)
    {
        return $user->currentTeam()->id === $account->team_id;
    }

    /**
     * Determine whether the user can delete the account.
     *
     * @param  User  $user
     * @param  Account  $account
     * @return mixed
     */
    public function delete(User $user, Account $account)
    {
        return $user->currentTeam()->id === $account->team_id;
    }
}
