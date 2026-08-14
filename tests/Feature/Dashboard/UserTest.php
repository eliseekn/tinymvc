<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

use App\Database\Entities\Role;
use App\Database\Models\RoleModel;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class UserTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_view_users_index(): void
    {
        $this
            ->auth(Fixtures::createAdmin())
            ->get('/dashboard/users')
            ->assertHttpStatusOk();
    }

    public function test_guest_can_not_view_users_index(): void
    {
        $this
            ->get('/dashboard/users')
            ->assertHttpStatusFound()
            ->assertRedirectedToUrl('/login');
    }

    public function test_admin_can_view_create_user_page(): void
    {
        $this
            ->auth(Fixtures::createAdmin())
            ->get('/dashboard/users/create')
            ->assertHttpStatusOk();
    }

    public function test_non_admin_can_not_view_create_user_page(): void
    {
        $this
            ->auth(Fixtures::createUser())
            ->get('/dashboard/users/create')
            ->assertHttpStatusOk();

        $this->assertStringContainsString('Forbidden', $this->getBody());
    }

    public function test_admin_can_create_user(): void
    {
        $role = RoleModel::factory()->create()->toEntity(Role::class);
        $email = faker()->unique()->safeEmail();

        $this
            ->auth(Fixtures::createAdmin())
            ->post('/dashboard/users', [
                'name' => faker()->name(),
                'email' => $email,
                'password' => 'P@ssw0rd',
                'role_id' => $role->getId(),
            ])
            ->assertHttpStatusFound()
            ->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_non_admin_can_not_create_user(): void
    {
        $role = RoleModel::factory()->create()->toEntity(Role::class);
        $email = faker()->unique()->safeEmail();

        $this
            ->auth(Fixtures::createUser())
            ->post('/dashboard/users', [
                'name' => faker()->name(),
                'email' => $email,
                'password' => 'P@ssw0rd',
                'role_id' => $role->getId(),
            ])
            ->assertHttpStatusOk();

        $this->assertStringContainsString('Forbidden', $this->getBody());
        $this->assertDatabaseDoesNotHave('users', ['email' => $email]);
    }

    public function test_admin_can_view_edit_user_page(): void
    {
        $user = Fixtures::createUser();

        $this
            ->auth(Fixtures::createAdmin())
            ->get('/dashboard/users/'.$user->getId().'/edit')
            ->assertHttpStatusOk();
    }

    public function test_admin_can_update_user(): void
    {
        $user = Fixtures::createUser();
        $name = faker()->name();

        $this
            ->auth(Fixtures::createAdmin())
            ->patch('/dashboard/users/'.$user->getId(), ['name' => $name])
            ->assertHttpStatusFound()
            ->assertDatabaseHas('users', ['name' => $name]);
    }

    public function test_non_admin_can_not_update_user(): void
    {
        $admin = Fixtures::createAdmin();
        $user = Fixtures::createUser();
        $name = faker()->name();

        $this
            ->auth($user)
            ->patch('/dashboard/users/'.$admin->getId(), ['name' => $name])
            ->assertHttpStatusOk();

        $this->assertStringContainsString('Forbidden', $this->getBody());
        $this->assertDatabaseDoesNotHave('users', ['name' => $name]);
    }

    public function test_admin_can_delete_user(): void
    {
        $user = Fixtures::createUser();

        $this
            ->auth(Fixtures::createAdmin())
            ->delete('/dashboard/users/'.$user->getId().'/delete')
            ->assertHttpStatusFound()
            ->assertDatabaseDoesNotHave('users', ['email' => $user->getEmail()]);
    }
}
