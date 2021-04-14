<?php

namespace App\Database\Seeds;

use App\Database\Repositories\Roles;
use Framework\Database\QueryBuilder;

class RoleSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'roles';

    /**
     * insert row
     *
     * @return void
     */
    public static function insert(): void
    {
        for ($i = 0; $i < count(Roles::ROLE); $i++) {
            QueryBuilder::table(self::$table)
                ->insert(['name' => Roles::ROLE[$i]])
                ->execute();
        }
    }
}
