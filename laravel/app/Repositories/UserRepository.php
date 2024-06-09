<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Two\User as SocialiteUser;

class UserRepository
{

    public function __construct()
    {
        throw_unless(config('auth.registration_enabled'), new AuthorizationException('Registration is disabled at this time'));
    }

    /**
     * @param SocialiteUser $socialiteUser
     * @return User
     */
    public function createFromSocialite(SocialiteUser $socialiteUser, $provider): User
    {
        return \DB::transaction(function () use ($socialiteUser, $provider) {

            // create user
            $user = new User();
            $user->name = ($socialiteUser->getName()) ? $socialiteUser->getName() : $socialiteUser->getNickname();
            $user->email = $socialiteUser->getEmail();
            $user->email_verified_at = now();
            $user->save();

            $this->createPersonalTeam($user);

            // create oauth provider
            $user->oauthProviders()->create([
                'provider' => $provider,
                'provider_user_id' => $socialiteUser->getId(),
                'access_token' => $socialiteUser->token,
                'refresh_token' => $socialiteUser->refreshToken,
            ]);

            event(new Registered($user));

            return $user;
        });
    }

    /**
     * @param $email string
     * @param $name string
     * @param $password string
     * @return User
     */
    public function createFromEmail($email, $name, $password): User {
        return \DB::transaction(function () use ($email, $name, $password) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

            $this->createPersonalTeam($user);

            return $user;
        });
    }

    /**
     * @param User $user
     * @return Team
     */
    protected function createPersonalTeam(User $user): Team{
        $team = new Team;
        $team->owner_id = $user->id;
        $team->name = 'Personal';
        $team->save();

        $team->users()->attach($user, ['role' => 'OWNER']);

        return $team;
    }
}
