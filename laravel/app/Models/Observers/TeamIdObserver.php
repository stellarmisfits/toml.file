<?php

namespace App\Models\Observers;

use App\Exceptions\NotAuthorizedTeam;

class TeamIdObserver
{

    /**
     * @param $model
     * @throws
     */
    public function saving($model)
    {
        $user =  auth()->user();

        if (!$user) {
            return;
        }

        $userTeam =  $user->currentTeam();
        $team = $model->team;

        throw_unless($userTeam->is($team), new NotAuthorizedTeam(
            $team->uuid,
            get_called_class(),
            'TeamIdObserver::checkCurrentTeam'
        ));
    }
}
