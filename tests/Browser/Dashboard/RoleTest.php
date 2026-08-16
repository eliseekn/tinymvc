<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser\Dashboard;

use App\Database\Entities\Role;
use App\Database\Entities\User;
use App\Database\Models\RoleModel;
use Core\Cache\Cache;
use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class RoleTest extends BrowserTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client->manage()->deleteAllCookies();
        Cache::forget('roles.dashboard');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Cache::forget('roles.dashboard');
        $this->refreshDatabase();
    }

    private function login(User $user): void
    {
        $this
            ->visit('/login')
            ->assertPageTitleSame(__('form.login'));

        $this->client->submit(
            $this->crawler
                ->selectButton('Submit')
                ->form([
                    'email' => $user->getEmail(),
                    'password' => 'P@ssw0rd',
                ])
        );

        $this->client->waitForElementToContain('h1', __('app.dashboard'));
        $this->crawler = $this->client->refreshCrawler();
    }

    public function test_can_view_roles_index(): void
    {
        $this->login(Fixtures::createAdmin(['email_verified_at' => carbon()->now()]));

        $this->client->click(
            $this->crawler->selectLink('Roles')->link()
        );

        $this->client->waitForElementToContain('h1', 'Roles');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertSelectorTextContains('h1', 'Roles');
    }

    public function test_guest_can_not_view_roles_index(): void
    {
        $this
            ->visit('/dashboard/roles')
            ->assertPageTitleSame(__('form.login'));
    }

    public function test_non_admin_can_not_access_create_role_page(): void
    {
        $this->login(Fixtures::createUser(['email_verified_at' => carbon()->now()]));

        $this->visit('/dashboard/roles/create');

        $this->assertSelectorTextContains('h1', 'Forbidden');
    }

    public function test_admin_can_update_role(): void
    {
        $this->login(Fixtures::createAdmin(['email_verified_at' => carbon()->now()]));

        $role = RoleModel::factory()->create()->toEntity(Role::class);

        $this->visit('/dashboard/roles/'.$role->getId().'/edit');

        $this->assertSelectorWillContain('h1', 'Edit role');

        $name = faker()->unique()->word();

        $this->client->submit(
            $this->crawler->selectButton('Submit')->form(['name' => $name])
        );

        $this->client->waitForVisibility('.alert.alert-success');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals('Role updated', $this->crawler->filter('.alert.alert-success')->text());
        $this->assertDatabaseHas('roles', ['name' => $name]);
    }

    public function test_admin_can_delete_role(): void
    {
        $this->login(Fixtures::createAdmin(['email_verified_at' => carbon()->now()]));

        $role = RoleModel::factory()->create()->toEntity(Role::class);

        $this->visit('/dashboard/roles');

        $this->findElement('form[action$="/dashboard/roles/'.$role->getId().'/delete"] button.delete')->click();

        $this->client->waitForVisibility('.swal2-popup');
        $this->findElement('.swal2-confirm')->click();

        $this->client->waitForVisibility('.alert.alert-success');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals('Role deleted', $this->crawler->filter('.alert.alert-success')->text());
        $this->assertDatabaseDoesNotHave('roles', ['name' => $role->getName()]);
    }
}
