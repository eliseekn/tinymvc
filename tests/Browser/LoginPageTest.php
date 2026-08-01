<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Browser;

use App\Database\Entities\User;
use App\Database\Models\UserModel;
use Core\Support\Config;
use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;

class LoginPageTest extends BrowserTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_authenticate(): void
    {
        $emailVerification = config('security.auth.email_verification');

        Config::updateEnv(['AUTH_EMAIL_VERIFICATION' => false]);

        $user = UserModel::factory()->create()->toEntity(User::class);

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

        $dashboard = __('app.dashboard');

        $this->client->waitForElementToContain('h1', $dashboard);
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals($dashboard, $this->crawler->filter('h1')->text());

        Config::updateEnv(['AUTH_EMAIL_VERIFICATION' => $emailVerification]);
    }
}
