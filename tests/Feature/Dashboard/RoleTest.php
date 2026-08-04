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
use Core\Cache\Cache;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class RoleTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget('roles.dashboard');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Cache::forget('roles.dashboard');
        $this->refreshDatabase();
    }

    public function test_can_view_roles_index(): void
    {
        $this
            ->auth(Fixtures::createUser())
            ->get('/dashboard/roles')
            ->assertHttpStatusOk();
    }

    public function test_guest_can_not_view_roles_index(): void
    {
        $this
            ->get('/dashboard/roles')
            ->assertHttpStatusFound()
            ->assertRedirectedToUrl('/login');
    }

    public function test_admin_can_view_create_role_page(): void
    {
        $this
            ->auth(Fixtures::createAdmin())
            ->get('/dashboard/roles/create')
            ->assertHttpStatusOk();
    }

    public function test_non_admin_can_not_view_create_role_page(): void
    {
        $this
            ->auth(Fixtures::createUser())
            ->get('/dashboard/roles/create')
            ->assertHttpStatusOk();

        $this->assertStringContainsString('Forbidden', $this->getBody());
    }

    public function test_admin_can_create_role(): void
    {
        $name = faker()->unique()->word();

        $this
            ->auth(Fixtures::createAdmin())
            ->post('/dashboard/roles', ['name' => $name])
            ->assertHttpStatusFound()
            ->assertDatabaseHas('roles', ['name' => $name]);
    }

    public function test_non_admin_can_not_create_role(): void
    {
        $name = faker()->unique()->word();

        $this
            ->auth(Fixtures::createUser())
            ->post('/dashboard/roles', ['name' => $name])
            ->assertHttpStatusOk();

        $this->assertStringContainsString('Forbidden', $this->getBody());
        $this->assertDatabaseDoesNotHave('roles', ['name' => $name]);
    }

    public function test_admin_can_view_edit_role_page(): void
    {
        $role = RoleModel::factory()->create()->toEntity(Role::class);

        $this
            ->auth(Fixtures::createAdmin())
            ->get('/dashboard/roles/'.$role->getId().'/edit')
            ->assertHttpStatusOk();
    }

    public function test_admin_can_update_role(): void
    {
        $role = RoleModel::factory()->create()->toEntity(Role::class);
        $name = faker()->unique()->word();

        $this
            ->auth(Fixtures::createAdmin())
            ->patch('/dashboard/roles/'.$role->getId(), ['name' => $name])
            ->assertHttpStatusFound()
            ->assertDatabaseHas('roles', ['name' => $name]);
    }

    public function test_admin_can_delete_role(): void
    {
        $role = RoleModel::factory()->create()->toEntity(Role::class);

        $this
            ->auth(Fixtures::createAdmin())
            ->delete('/dashboard/roles/'.$role->getId().'/delete')
            ->assertHttpStatusFound()
            ->assertDatabaseDoesNotHave('roles', ['name' => $role->getName()]);
    }
}
