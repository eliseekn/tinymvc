<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Factories;

use App\Database\Models\Role;
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
            'password' => bcrypt('password'),
            'email_verified_at' => null,
            'role_id' => Role::findByName(UserRole::USER->value)?->getId(),
            'avatar' => null,
        ];
    }
}
