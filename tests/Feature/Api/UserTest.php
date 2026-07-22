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
use App\Database\Models\RoleModel;
use App\Database\Models\UserModel;
use App\Database\Seeders\RoleSeeder;
use App\Enums\UserRole;
use Core\Database\Factory\Factory;
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
        $user = UserModel::factory()->make([
            'password' => 'P@ssw0rd',
            'role_id' => RoleModel::findByName(UserRole::USER->value)?->getId(),
        ])->toEntity(User::class);

        $this
            ->auth(Fixtures::createAdmin())
            ->postJson('/api/v1/users', $user->toArray())
            ->assertStatusEquals(HttpCode::CREATED)
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'message' => 'User created',
                'data' => $user->toArray(),
            ])
            ->assertDatabaseHas('users', ['name' => $user->getName()]);
    }

    public function test_can_get_collection(): void
    {
        $users = Factory::for(UserModel::class, 10)->create();
        $users = array_map(fn (UserModel $u) => $u->toEntity(User::class), $users);

        $this
            ->auth(Fixtures::createAdmin())
            ->getJson('/api/v1/users')
            ->assertStatusOk()
            ->assertJsonContains([
                [
                    'name' => $users[0]->getName(),
                    'email' => $users[0]->getEmail(),
                ],
            ]);
    }

    public function test_can_get_item(): void
    {
        $user = UserModel::factory()->create()->toEntity(User::class);

        $this
            ->auth(Fixtures::createAdmin())
            ->getJson('/api/v1/users/'.$user->getId())
            ->assertStatusOk()
            ->assertJsonContains([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]);
    }

    public function test_can_update(): void
    {
        $user = UserModel::factory()->create()->toEntity(User::class);
        $name = faker()->name();

        $this
            ->auth(Fixtures::createAdmin())
            ->patchJson('/api/v1/users/'.$user->getId(), ['name' => $name])
            ->assertStatusOk()
            ->assertJsonContains(['data' => array_merge(['name' => $name], $user->toArray())])
            ->assertDatabaseHas('users', ['name' => $name]);
    }

    public function test_can_delete(): void
    {
        $user = UserModel::factory()->create()->toEntity(User::class);

        $this
            ->auth(Fixtures::createAdmin())
            ->deleteJson('/api/v1/users/'.$user->getId())
            ->assertStatusEquals(HttpCode::NO_CONTENT)
            ->assertJsonContains(['status' => ResponseStatus::SUCCESS])
            ->assertDatabaseDoesNotHave('users', ['name' => $user->getName()]);
    }
}
