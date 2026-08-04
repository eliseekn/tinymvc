<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit\Entities;

use App\Database\Entities\User;
use App\Enums\UserRole;
use Carbon\Carbon;
use Core\Support\Encryption;
use Core\Testing\Traits\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_setting_password_hashes_it(): void
    {
        $user = new User;
        $user->setPassword('P@ssw0rd');

        $this->assertNotEquals('P@ssw0rd', $user->getPassword());
        $this->assertTrue(Encryption::check('P@ssw0rd', $user->getPassword()));
    }

    public function test_has_verified_email_is_false_by_default(): void
    {
        $user = new User;

        $this->assertFalse($user->hasVerifiedEmail());
    }

    public function test_has_verified_email_is_true_once_set(): void
    {
        $user = new User;
        $user->setEmailVerifiedAt(Carbon::now());

        $this->assertTrue($user->hasVerifiedEmail());
    }

    public function test_getters_and_setters_round_trip(): void
    {
        $user = new User;
        $user
            ->setName('John Doe')
            ->setEmail('john@example.com')
            ->setAvatar('avatar.jpg')
            ->setRoleId(2);

        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
        $this->assertEquals('avatar.jpg', $user->getAvatar());
        $this->assertEquals(2, $user->getRoleId());
    }

    public function test_role_is_null_when_role_id_not_set(): void
    {
        $user = new User;

        $this->assertNull($user->role());
    }

    public function test_role_returns_associated_role(): void
    {
        $user = Fixtures::createUser();

        $role = $user->role();

        $this->assertNotNull($role);
        $this->assertEquals(UserRole::USER->value, $role->getName());
    }
}
