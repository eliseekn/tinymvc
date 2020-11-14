<?php

namespace App\Helpers;

use Framework\HTTP\Request;
use App\Database\Models\ActivitiesModel;

class ActivityHelper
{    
    /**
     * log user action
     *
     * @param  string $user
     * @param  string $action
     * @return void
     */
    public static function log(string $user, string $action): void
    {
        ActivitiesModel::insert([
            'user' => $user,
            'url' => Request::getFullUri(),
            'method' => Request::getMethod(),
            'ip_address' => Request::getRemoteIP(),
            'action' => $action
        ]);
    }
}
