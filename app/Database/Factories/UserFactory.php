<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Factories;

use App\Database\Models\User;
use App\Enums\UserRole;
use Carbon\Carbon;
use Core\Database\Factory;

class UserFactory extends Factory
{
    public function __construct(int $count = 1)
    {
        parent::__construct(User::class, $count);
    }

    public function data(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->email(),
            'password' => hash_pwd('password'),
            'email_verified' => Carbon::now()->toDateTimeString(),
            'role' => UserRole::USER->value
        ];
    }
}
