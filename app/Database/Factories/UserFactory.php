<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Factories;

use Carbon\Carbon;
use Core\Database\Factory;
use App\Database\Models\User;

class UserFactory extends Factory
{
    public static string $model = User::class;

    public function __construct(int $count = 1)
    {
        parent::__construct($count);
    }

    public function data(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->email(),
            'password' => hash_pwd('password'),
            'email_verified' => Carbon::now()->toDateTimeString(),
            'role' => User::ROLE_USER
        ];
    }
}
