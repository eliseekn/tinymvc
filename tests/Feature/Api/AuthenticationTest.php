<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Entities\User;
use App\Database\Models\UserModel;
use App\Events\UserRegistered\UserRegisteredEvent;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Event\Event;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class AuthenticationTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_register(): void
    {
        Event::fake(UserRegisteredEvent::class);

        $user = Fixtures::makeUser(['password' => 'P@ssw0rd']);

        $this
            ->postJson('/api/v1/auth/register', [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
            ])
            ->assertHttpStatusEquals(HttpCode::CREATED)
            ->assertDatabaseHas('users', ['email' => $user->getEmail()]);

        Event::assertDispatched(UserRegisteredEvent::class);
    }

    public function test_can_not_register_twice(): void
    {
        $user = Fixtures::createUser(['password' => 'P@ssw0rd']);

        $this
            ->postJson('/api/v1/auth/register', [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => 'P@ssw0rd',
            ])
            ->assertHttpStatusEquals(HttpCode::BAD_REQUEST);
    }

    public function test_can_login(): void
    {
        $user = UserModel::factory()->create()->toEntity(User::class);

        $this
            ->postJson('/api/v1/auth/login', [
                'email' => $user->getEmail(),
                'password' => 'P@ssw0rd',
            ])
            ->assertHttpStatusOk()
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'user' => $user->toArray(),
            ]);
    }

    public function test_admin_can_login(): void
    {
        $admin = Fixtures::createAdmin();

        $this
            ->postJson('/api/v1/auth/admin/login', [
                'username' => $admin->getUsername(),
                'password' => 'P@ssw0rd',
            ])
            ->assertHttpStatusOk()
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'admin' => $admin->toArray(),
            ]);
    }

    public function test_admin_can_not_login_with_user_credentials(): void
    {
        $user = Fixtures::createUser();

        $this
            ->postJson('/api/v1/auth/admin/login', [
                'username' => $user->getEmail(),
                'password' => 'P@ssw0rd',
            ])
            ->assertHttpStatusEquals(HttpCode::UNAUTHORIZED);
    }

    public function test_can_logout(): void
    {
        $user = UserModel::factory()->create()->toEntity(User::class);

        $this
            ->auth($user)
            ->postJson('/api/v1/auth/logout')
            ->assertHttpStatusOk()
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'message' => 'Logout successfully',
            ]);
    }
}
