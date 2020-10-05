<?php

namespace App\Database\Models;

use Framework\ORM\Model;
use Framework\ORM\Query;

class RolesModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'roles';
    
    /**
     * get users from date range
     *
     * @param  string $start
     * @param  string $end
     * @return mixed
     */
    public static function findDateRange(string $start, string $end)
    {
        return Query::DB()
            ->select('*')
            ->from(static::$table)
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($start)))
            ->and('created_at', '<=', date('Y-m-d H:i:s', strtotime($end)))
            ->orderBy('name', 'ASC')
            ->fetchAll();
    }
}
