<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Models\UserModel;
use Core\Support\File;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Exception;
use Tests\Fixtures;

class ProfileTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        if (storage(config('storage.uploads'))->isFile('avatar.jpg')) {
            storage(config('storage.uploads'))->deleteFile('avatar.jpg');
        }

        $this->refreshDatabase();
    }

    private function generateAvatar(): string
    {
        $avatar = storage(config('storage.uploads'))->file('avatar.jpg');

        if (! File::generateImage($avatar, 100, 100)) {
            throw new Exception('Failed to generate image file.');
        }

        return $avatar;
    }

    public function test_user_can_update_own_profile(): void
    {
        $user = Fixtures::createUser();
        $name = faker()->name();

        $this->auth($user)
            ->patchJson('/api/v1/account/'.$user->getId().'/profile', ['name' => $name])
            ->assertHttpStatusOk()
            ->assertDatabaseHas('users', ['name' => $name]);
    }

    public function test_admin_can_update_another_users_profile(): void
    {
        $admin = Fixtures::createAdmin();
        $user = Fixtures::createUser();
        $name = faker()->name();

        $this->auth($admin)
            ->patchJson('/api/v1/account/'.$user->getId().'/profile', ['name' => $name])
            ->assertHttpStatusOk()
            ->assertDatabaseHas('users', ['name' => $name]);
    }

    public function test_user_can_not_update_another_users_profile(): void
    {
        $user = Fixtures::createUser();
        $otherUser = Fixtures::createUser();

        $this->auth($user)
            ->patchJson('/api/v1/account/'.$otherUser->getId().'/profile', ['name' => faker()->name()])
            ->assertHttpStatusForbidden();
    }

    public function test_unauthenticated_can_not_update_profile(): void
    {
        $user = Fixtures::createUser();

        $this
            ->patchJson('/api/v1/account/'.$user->getId().'/profile', ['name' => faker()->name()])
            ->assertHttpStatusUnauthenticated();
    }

    public function test_user_can_delete_own_avatar(): void
    {
        $user = Fixtures::createUser(['avatar' => 'avatar.jpg']);
        $avatar = $this->generateAvatar();

        $this->auth($user)
            ->deleteJson('/api/v1/account/'.$user->getId().'/profile/avatar')
            ->assertHttpStatusOk();

        $this->assertFileDoesNotExist($avatar);
        $this->assertNull(UserModel::find((int) $user->getId())->getAvatar());
    }

    public function test_admin_can_not_delete_another_users_avatar(): void
    {
        $admin = Fixtures::createAdmin();
        $user = Fixtures::createUser(['avatar' => 'avatar.jpg']);
        $avatar = $this->generateAvatar();

        $this->auth($admin)
            ->deleteJson('/api/v1/account/'.$user->getId().'/profile/avatar')
            ->assertHttpStatusForbidden();

        $this->assertFileExists($avatar);
        $this->assertEquals('avatar.jpg', UserModel::find((int) $user->getId())->getAvatar());
    }
}
