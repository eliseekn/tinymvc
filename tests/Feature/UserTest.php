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
use App\Database\Seeders\RoleSeeder;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;

class UserTest extends FeatureTestCase
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

    public function test_can_update_profile(): void
    {
        $user = User::factory()->create();

        $this
            ->auth($user)
            ->patch('/dashboard/profile', [
                'avatar' => $this->file(storage(config('storage.tmp'))->file('avatar.jpg')),
            ])
            ->assertStatusFound()
            ->assertDatabaseHas('users', ['avatar' => User::find($user->getId())->get('avatar')]);

        $this->assertFileExists(storage(config('storage.uploads'))->file('avatar.jpg'));
    }
}
