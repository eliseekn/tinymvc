<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Dashboard;

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

    public function test_can_view_profile_page(): void
    {
        $this
            ->auth(Fixtures::createUser())
            ->get('/dashboard/profile')
            ->assertHttpStatusOk();
    }

    public function test_guest_can_not_view_profile_page(): void
    {
        $this
            ->get('/dashboard/profile')
            ->assertHttpStatusFound()
            ->assertRedirectedToUrl('/login');
    }

    public function test_can_delete_own_avatar(): void
    {
        $user = Fixtures::createUser(['avatar' => 'avatar.jpg']);
        $avatar = storage(config('storage.uploads'))->file('avatar.jpg');

        if (! File::generateImage($avatar, 100, 100)) {
            throw new Exception('Failed to generate image file.');
        }

        $this
            ->auth($user)
            ->delete('/dashboard/profile/'.$user->getId().'/avatar')
            ->assertHttpStatusFound();

        $this->assertFileDoesNotExist($avatar);
        $this->assertNull(UserModel::find((int) $user->getId())->getAvatar());
    }
}
