<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser;

use App\Database\Seeders\RoleSeeder;
use App\Events\UserCreated\UserCreatedEvent;
use Core\Event\Event;
use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class CreateUserTest extends BrowserTestCase
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

    public function test_can_create(): void
    {
        Event::fake(UserCreatedEvent::class);

        $admin = Fixtures::createAdmin();

        $this
            ->visit('/login')
            ->assertPageTitleSame(__('form.login'));

        $this->client->submit(
            $this->crawler
                ->selectButton('Submit')
                ->form([
                    'email' => $admin->getAttributes('email'),
                    'password' => 'P@ssw0rd',
                ])
        );

        $this->client->waitForElementToContain('h1', __('app.dashboard'));
        $this->crawler = $this->client->refreshCrawler();

        $this->client->click(
            $this->crawler->selectLink('Users')->link()
        );

        $this->client->waitForElementToContain('h1', 'Users');
        $this->crawler = $this->client->refreshCrawler();

        $this->client->click(
            $this->crawler->selectLink('Add new')->link()
        );

        $this->client->waitForElementToContain('h1', 'Create user');
        $this->crawler = $this->client->refreshCrawler();

        $data = [
            'name' => faker()->name(),
            'email' => faker()->unique()->safeEmail(),
            'password' => 'P@ssw0rd',
        ];

        $this->client->submit(
            $this->crawler->selectButton('Submit')->form($data)
        );

        $this->client->waitForVisibility('.alert.alert-success');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals('User created', $this->crawler->filter('.alert.alert-success')->text());
        $this->assertDatabaseHas('users', ['email' => $data['email']]);

        Event::assertDispatched(UserCreatedEvent::class);
    }
}
