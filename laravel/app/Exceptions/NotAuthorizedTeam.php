<?php

namespace App\Exceptions;

use Exception;

/**
 * Class NotAuthorizedTeam
 * @package App\Exceptions
 */
class NotAuthorizedTeam extends Exception
{
    /**
     * NotAuthorizedTeam constructor.
     * @param string $team_uuid
     * @param string $model_name
     * @param string $action
     */
    public function __construct($team_uuid, $model_name, $action) {
        $msg = $action . ' error on ' . $model_name .
            '. You do not have permission to modify records for team_id: ' . $team_uuid;

        parent::__construct($msg, $code = 403,null);
    }
}
