<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Models\Role;
use App\Database\Models\User;
use App\Database\Seeders\RoleSeeder;
use App\Enums\UserRole;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class UserTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        RoleSeeder::run();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_store(): void
    {
        $user = User::factory()->make([
            'password' => 'P@ssw0rd',
            'role_id' => Role::findByName(UserRole::USER->value)?->getId(),
        ]);

        $this
            ->auth(Fixtures::createAdmin())
            ->postJson('/api/v1/users', $user->get())
            ->assertStatusEquals(HttpCode::CREATED)
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'message' => 'User created',
                'data' => $user->get(),
            ])
            ->assertDatabaseHas('users', ['name' => $user->get('name')]);
    }

    public function test_can_get_collection(): void
    {
        $users = User::factory(10)->create();

        $this
            ->auth(Fixtures::createAdmin())
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
        $user = User::factory()->create();

        $this
            ->auth(Fixtures::createAdmin())
            ->getJson('/api/v1/users/'.$user->getId())
            ->assertStatusOk()
            ->assertJsonContains([
                'name' => $user->get('name'),
                'email' => $user->get('email'),
            ]);
    }

    public function test_can_update(): void
    {
        $user = User::factory()->create([
            'role_id' => Role::findByName(UserRole::USER->value)?->getId(),
        ]);
        $name = faker()->name();

        $this
            ->auth(Fixtures::createAdmin())
            ->patchJson('/api/v1/users/'.$user->getId(), ['name' => $name])
            ->assertStatusOk()
            ->assertJsonContains(['data' => $user->set(['name' => $name])->get()])
            ->assertDatabaseHas('users', ['name' => $name]);
    }

    public function test_can_delete(): void
    {
        $user = User::factory()->create();

        $this
            ->auth(Fixtures::createAdmin())
            ->deleteJson('/api/v1/users/'.$user->getId())
            ->assertStatusEquals(HttpCode::NO_CONTENT)
            ->assertJsonContains(['status' => ResponseStatus::SUCCESS])
            ->assertDatabaseDoesNotHave('users', ['name' => $user->get('name')]);
    }
}
