<?php

namespace App\Database\Models;

use App\Helpers\Auth;
use Framework\Database\Model;

class Notifications
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

    /**
     * retrieves all notifications messages
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public static function paginate(int $items_per_pages = 20): \Framework\Support\Pager
    {
        return self::model()
            ->findBy('user_id', Auth::get()->id)
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves unread notifications messages count
     *
     * @return int
     */
    public static function unreadCount(): int
    {
        return self::model()
            ->count()
            ->where('status', 'unread')
            ->and('user_id', Auth::get()->id)
            ->single()
            ->value;
    }
}
