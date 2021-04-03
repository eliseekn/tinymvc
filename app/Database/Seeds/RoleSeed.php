<?php

namespace App\Database\Seeds;

use App\Database\Models\Roles;
use Framework\Database\Builder;

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
            Builder::insert(self::$table, ['name' => Roles::ROLE[$i]])->execute();
        }
    }
}
