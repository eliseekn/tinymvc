<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser;

use App\Database\Models\User;
use App\Database\Seeders\RoleSeeder;
use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;

class LoginPageTest extends BrowserTestCase
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

    public function test_can_authenticate(): void
    {
        $user = User::factory()->create();

        $this
            ->visit('/login')
            ->assertPageTitleSame(__('form.login'));

        $this->client->submit(
            $this->crawler
                ->selectButton('Submit')
                ->form([
                    'email' => $user->getAttributes('email'),
                    'password' => 'P@ssw0rd',
                ])
        );

        $dashboard = __('app.dashboard');

        $this->client->waitForElementToContain('h1', $dashboard);
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals($dashboard, $this->crawler->filter('h1')->text());
    }
}
