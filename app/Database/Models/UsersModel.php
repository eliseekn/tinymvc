<?php

namespace App\Database\Models;

use Framework\ORM\Model;

class UsersModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'users';
    
    /**
     * get users from date range
     *
     * @param  string $start
     * @param  string $end
     * @return mixed
     */
    public static function findDateRange(string $start, string $end)
    {
        return self::select()
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($start)))
            ->and('created_at', '<=', date('Y-m-d H:i:s', strtotime($end)))
            ->orderBy('name', 'ASC')
            ->all();
    }
}
