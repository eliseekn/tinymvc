<?php

namespace App\Database\Seeds;

use Faker\Factory;
use Framework\ORM\Seeder;

/**
 * UserSeed
 * 
 * Insert new user row
 */
class UserSeed extends Seeder
{
    /**
     * insert user row
     *
     * @return void
     */
    public function sow(): void
    {
        $faker = Factory::create('en_US');

        $this->insert('users', [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => hash_string('admin'),
            'role' => 'administrator'
        ]);
    }
}