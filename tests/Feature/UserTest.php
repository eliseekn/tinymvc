<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature;

use Core\Support\File;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Exception;
use Tests\Fixtures;

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
        $user = Fixtures::createUser();
        $avatar = storage(config('storage.tmp'))->file('avatar.jpg');

        if (! File::generateImage($avatar, 100, 100)) {
            throw new Exception('Failed to generate image file.');
        }

        $this->auth($user)
            ->patch('/dashboard/profile/'.$user->getId(), [
                'avatar' => $this->file($avatar),
            ])
            ->assertHttpStatusFound()
            ->assertDatabaseHas('users', ['avatar' => File::getBasename($avatar)]);

        $this->assertFileExists($avatar);
    }
}
