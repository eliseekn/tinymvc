<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Tests\Feature;

use App\Database\Models\User;
use Core\Testing\FeatureTestCase;
use Core\Testing\RefreshDatabase;

class UserTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->refreshDatabase();

        parent::tearDown();
    }

    public function test_can_update_profile(): void
    {
        $user = User::factory()->create();

        $this
            ->auth($user)
            ->patch('/dashboard/profile', [
                'avatar' => $this->createFileUpload('avatar.jpg')
            ])
            ->assertStatusFound()
            ->assertIsFile(storage(config('storage.uploads'))->file('avatar.jpg'))
            ->assertDatabaseHas('users', ['avatar' => User::find($user->getId())->get('avatar')]);
    }
}
