<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Tests\Feature\Api;

use App\Database\Models\User;
use App\Enums\UserRole;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Testing\FeatureTestCase;
use Core\Testing\RefreshDatabase;

class UserTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->refreshDatabase();

        parent::tearDown();
    }

    public function test_can_store(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->auth($admin)
            ->postJson('/api/v1/users', $user->get())
            ->assertStatusEquals(HttpCode::CREATED)
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'data' => 'User created',
            ]);
    }

    public function test_can_get_collection(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $users = User::factory(10)->create();

        $this
            ->auth($admin)
            ->getJson('/api/v1/users')
            ->assertStatusOk()
            ->assertJsonContains([
                [
                    'name' => $users[0]->get('name'),
                    'email' => $users[0]->get('email'),
                ],
            ]);
    }

    public function test_can_get_item(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
        $user = User::factory()->create();

        $this
            ->auth($admin)
            ->getJson('/api/v1/users/' . $user->getId())
            ->assertStatusOk()
            ->assertJsonContains([
                'name' => $user->get('name'),
                'email' => $user->get('email'),
            ]);
    }
}
