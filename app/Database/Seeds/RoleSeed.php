<?php

namespace App\Database\Seeds;

use Framework\Database\Seeder;
use App\Database\Models\RolesModel;

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
        for ($i = 0; $i < count(RolesModel::ROLE); $i++) {
            Seeder::insert(self::$table, ['name' => RolesModel::ROLE[$i]]);
        }
    }
}
