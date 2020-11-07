<?php

namespace App\Helpers;

use Framework\HTTP\Request;
use App\Database\Models\ActivitiesModel;

class ActivityHelper
{    
    /**
     * log user action
     *
     * @param  string $action
     * @param  string $target
     * @return void
     */
    public static function log(string $action): void
    {
        ActivitiesModel::insert([
            'user' => AuthHelper::getSession()->email,
            'url' => Request::getFullUri(),
            'method' => Request::getMethod(),
            'ip_address' => Request::getRemoteIP(),
            'action' => $action
        ]);
    }
}
