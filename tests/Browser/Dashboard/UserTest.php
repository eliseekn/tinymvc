<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser\Dashboard;

use App\Database\Entities\User;
use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class UserTest extends BrowserTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client->manage()->deleteAllCookies();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

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

    public function test_can_view_users_index(): void
    {
        $this->login(Fixtures::createAdmin(['email_verified_at' => carbon()->now()]));

        $this->client->click(
            $this->crawler->selectLink('Users')->link()
        );

        $this->client->waitForElementToContain('h1', 'Users');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertSelectorTextContains('h1', 'Users');
    }

    public function test_guest_can_not_view_users_index(): void
    {
        $this
            ->visit('/dashboard/users')
            ->assertPageTitleSame(__('form.login'));
    }

    public function test_non_admin_can_not_access_create_user_page(): void
    {
        $this->login(Fixtures::createUser(['email_verified_at' => carbon()->now()]));

        $this->visit('/dashboard/users/create');

        $this->assertSelectorTextContains('h1', 'Forbidden');
    }

    public function test_admin_can_delete_user(): void
    {
        $this->login(Fixtures::createAdmin(['email_verified_at' => carbon()->now()]));

        $user = Fixtures::createUser();

        $this->visit('/dashboard/users');

        $this->findElement('form[action$="/dashboard/users/'.$user->getId().'/delete"] button.delete')->click();

        $this->client->waitForVisibility('.swal2-popup');
        $this->findElement('.swal2-confirm')->click();

        $this->client->waitForVisibility('.alert.alert-success');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals('User deleted', $this->crawler->filter('.alert.alert-success')->text());
        $this->assertDatabaseDoesNotHave('users', ['email' => $user->getEmail()]);
    }
}
