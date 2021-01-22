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
        Seeder::insert(self::$table, [
            'title' => 'Administrator',
            'slug' => RolesModel::ROLES[0],
            'description' => 'Can access administration dashboard and has all permissions'
        ]);

        Seeder::insert(self::$table, [
            'title' => 'Customer',
            'slug' => RolesModel::ROLES[1],
            'description' => 'Can access administration dashboard but has not all permissions'
        ]);

        Seeder::insert(self::$table, [
            'title' => 'Visitor',
            'slug' => RolesModel::ROLES[2],
            'description' => 'Can only view website pages and post comments'
        ]);
    }
}
