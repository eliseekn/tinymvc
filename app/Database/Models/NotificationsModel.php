<?php

namespace App\Database\Models;

use App\Helpers\Auth;
use Framework\Database\Model;

class NotificationsModel
{    
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'notifications';
    
    /**
     * create new model instance 
     *
     * @return \Framework\Database\Model
     */
    private static function model(): \Framework\Database\Model
    {
        return new Model(self::$table);
    }

    /**
     * get notifications messages
     *
     * @param  int $limit
     * @return array
     */
    public static function findMessages(int $limit = 5)
    {
        return self::model()
            ->findBy('status', 'unread')
            ->and('user_id', Auth::get()->id)
            ->oldest()
            ->take($limit);
    }
}
