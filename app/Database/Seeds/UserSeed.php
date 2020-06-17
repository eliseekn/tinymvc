<?php

namespace App\Database\Seeds;

use Faker\Factory;
use Framework\ORM\Seeder;

class UserSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'users';

    /**
     * insert row
     *
     * @return void
     */
    public static function insert(): void
    {
        $faker = Factory::create();

        Seeder::insert(self::$table, [
            'email' => $faker->email,
            'password' => hash_string('password')
        ]);
    }
}