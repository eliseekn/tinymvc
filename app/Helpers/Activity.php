<?php

namespace App\Helpers;

use Framework\Http\Request;
use App\Database\Repositories\Activities;

class Activity
{    
    /**
     * log user action
     *
     * @param  string $action
     * @param  string|null $user
     * @return void
     */
    public static function log(string $action, ?string $user = null): void
    {
        (new Activities())->store($user, $action, new Request());
    }
}
