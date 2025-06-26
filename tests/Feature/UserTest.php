<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature;

use App\Database\Models\User;
use Core\Support\File;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Exception;

class UserTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        storage(config('storage.tmp'))->deleteFile('avatar.jpg');
        storage(config('storage.uploads'))->deleteFile('avatar.jpg');

        $this->refreshDatabase();
    }

    public function test_can_update_profile(): void
    {
        $user = User::factory()->create();
        $avatar = storage(config('storage.tmp'))->file('avatar.jpg');

        if (! File::generateImage($avatar, 100, 100)) {
            throw new Exception('Failed to generate image file.');
        }

        $this
            ->auth($user)
            ->patch('/dashboard/profile', [
                'avatar' => $this->file($avatar),
            ])
            ->assertStatusFound()
            ->assertDatabaseHas('users', ['avatar' => File::getBasename($avatar)]);

        $this->assertFileExists($avatar);
    }
}
