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
use Core\Database\Model;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_user_instance(): void
    {
        $data = [
            'name' => faker()->name(),
            'email' => faker()->unique()->email(),
            'password' => bcrypt('password'),
            'email_verified_at' => null,
            'role' => UserRole::USER->value,
            'avatar' => null,
        ];

        $user = User::factory()->make($data);

        $this->assertInstanceOf(Model::class, $user);
        $this->assertEquals($data, $user->get());
    }
}
