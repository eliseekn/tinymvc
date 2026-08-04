<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser;

use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class EditUserTest extends BrowserTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_edit(): void
    {
        $admin = Fixtures::createAdmin();
        $user = Fixtures::createUser();

        $this
            ->visit('/login')
            ->assertPageTitleSame(__('form.login'));

        $this->client->submit(
            $this->crawler
                ->selectButton('Submit')
                ->form([
                    'email' => $admin->getEmail(),
                    'password' => 'P@ssw0rd',
                ])
        );

        $this->client->waitForElementToContain('h1', __('app.dashboard'));

        $this->visit('/dashboard/users/'.$user->getId().'/edit');

        $this->assertSelectorWillContain('h1', 'Edit user');

        $name = faker()->name();

        $this->client->submit(
            $this->crawler->selectButton('Submit')->form(['name' => $name])
        );

        $this->client->waitForVisibility('.alert.alert-success');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals('User updated', $this->crawler->filter('.alert.alert-success')->text());
        $this->assertDatabaseHas('users', ['name' => $name]);
    }
}
