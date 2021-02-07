<?php

namespace App\Database\Models;

use Framework\Database\Model;
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
     * @return \Framework\Database\Model
     */
    public static function messages(): \Framework\Database\Model
    {
        return self::select()
            ->where('status', 'unread')
            ->and('user_id', Auth::get()->id)
            ->orderDesc('created_at');
    }
}
