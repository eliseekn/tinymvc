<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace App\Seeds;

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
            'password' => hash_string('password'),
            'role' => 'user'
        ]);
    }
}