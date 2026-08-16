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
use App\Database\Models\UserModel;
use Core\Support\File;
use Core\Testing\BrowserTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Exception;
use Tests\Fixtures;

class ProfileTest extends BrowserTestCase
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

        if (storage(config('storage.uploads'))->isFile('avatar.jpg')) {
            storage(config('storage.uploads'))->deleteFile('avatar.jpg');
        }

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

    public function test_can_view_profile_page(): void
    {
        $this->login(Fixtures::createUser(['email_verified_at' => carbon()->now()]));

        $this->visit('/dashboard/profile');

        $this->assertSelectorTextContains('h1', 'Edit profile');
    }

    public function test_guest_can_not_view_profile_page(): void
    {
        $this
            ->visit('/dashboard/profile')
            ->assertPageTitleSame(__('form.login'));
    }

    public function test_can_delete_own_avatar(): void
    {
        $user = Fixtures::createUser([
            'email_verified_at' => carbon()->now(),
            'avatar' => 'avatar.jpg',
        ]);

        $avatar = storage(config('storage.uploads'))->file('avatar.jpg');

        if (! File::generateImage($avatar, 100, 100)) {
            throw new Exception('Failed to generate image file.');
        }

        $this->login($user);

        $this->visit('/dashboard/profile');

        $this->findElement('#delete-avatar')->click();

        $this->client->waitForVisibility('.swal2-popup');
        $this->findElement('.swal2-confirm')->click();

        $this->client->waitForVisibility('.alert.alert-success');
        $this->crawler = $this->client->refreshCrawler();

        $this->assertEquals('Profile updated', $this->crawler->filter('.alert.alert-success')->text());
        $this->assertFileDoesNotExist($avatar);
        $this->assertNull(UserModel::find((int) $user->getId())->getAvatar());
    }
}
