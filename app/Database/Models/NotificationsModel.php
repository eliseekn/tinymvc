<?php

namespace App\Database\Models;

use Framework\ORM\Model;
use App\Helpers\Auth;

class NotificationsModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'notifications';
    
    /**
     * get notifications
     *
     * @return \Framework\ORM\Model
     */
    public static function get(): \Framework\ORM\Model
    {
        return self::select()
            ->where('status', 'unread')
            ->andWhere('user_id', Auth::user()->id)
            ->orderDesc('created_at');
    }
}
