<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Factories;

use App\Database\Models\User;
use Core\Database\Factory;

class UserFactory extends Factory
{
    public static $model = User::class;

    public function __construct(int $count = 1)
    {
        parent::__construct($count);
    }

    public function data()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->email(),
            'password' => hash_pwd('password'),
            'verified' => true,
            'role' => User::ROLE_USER
        ];
    }
}
