<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Factories;

use App\Database\Models\User;
use App\Enums\UserRole;
use Core\Database\Factory\Factory;

class UserFactory extends Factory
{
    public string $model = User::class;

    public function __construct(int $count = 1)
    {
        parent::__construct($this->model, $count);
    }

    public function data(): array
    {
        return [
            'name' => faker()->name(),
            'email' => faker()->unique()->email(),
            'password' => hash_pwd('password'),
            'email_verified' => carbon()->toDateTimeString(),
            'role' => UserRole::USER->value,
            'created_at' => carbon(faker()->dateTimeBetween('-12 months'))->toDateTimeString()
        ];
    }
}
