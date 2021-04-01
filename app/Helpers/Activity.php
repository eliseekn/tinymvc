<?php

namespace App\Helpers;

use Framework\Http\Request;
use Framework\Database\Model;

class Activity
{    
    /**
     * log user action
     *
     * @param  string $action
     * @param  string $user
     * @return void
     */
    public static function log(string $action, ?string $user = null): void
    {
        (new Model('activities'))->insert([
            'user' => is_null($user) ? Auth::get()->email : $user,
            'url' => Request::getFullUri(),
            'method' => Request::getMethod(),
            'ip_address' => Request::getRemoteIP(),
            'action' => $action
        ]);
    }
}
