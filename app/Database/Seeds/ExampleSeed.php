<?php

namespace App\Database\Seeds;

use Faker\Factory;
use Framework\ORM\Seeder;

/**
 * ExampleSeed
 * 
 */
class ExampleSeed extends Seeder
{
    /**
     * insert row
     *
     * @return void
     */
    public function sow(): void
    {
        $faker = Factory::create('en_US');

        $this->insert('name_of_table', [
            'username' => $faker->name,
            'email' => $faker->email,
            'password' => hash_string('administrator')
        ]);
    }
}