<?php

namespace App\Database\Seeds;

use Faker\Factory;
use Framework\ORM\Seeder;

/**
 * ExampleSeed
 */
class ExampleSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'name_of_table';

    /**
     * insert row
     *
     * @return void
     */
    public function insert(): void
    {
        $faker = Factory::create('en_US');

        Seeder::insert($this->table, [
            'username' => $faker->name,
            'email' => $faker->email,
            'password' => hash_string('administrator')
        ]);
    }
}