<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit;

use App\Database\Models\User;
use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_user_data_is_valide(): void
    {
        $data = [
            'name' => faker()->name(),
            'email' => faker()->unique()->email(),
            'password' => hash_pwd('password'),
            'email_verified_at' => null,
            'role' => UserRole::USER->value,
            'avatar' => null,
        ];

        $user = User::factory()->make($data);

        $this->assertEquals($data, $user->get());
    }
}
