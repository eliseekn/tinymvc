<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Models\User;
use Core\Enums\ResponseStatus;
use Core\Testing\FeatureTestCase;
use Core\Testing\RefreshDatabase;

class AuthenticationTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->refreshDatabase();

        parent::tearDown();
    }

    public function test_can_login(): void
    {
        $user = User::factory()->create();

        $this
            ->postJson('/api/v1/login', [
                'email' => $user->get('email'),
                'password' => 'password',
            ])
            ->assertStatusOk()
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'user' => $user->get(),
            ]);
    }

    public function test_can_logout(): void
    {
        $user = User::factory()->create();

        $this
            ->auth($user)
            ->postJson('/api/v1/logout')
            ->assertStatusOk()
            ->assertJsonContains([
                'status' => ResponseStatus::SUCCESS,
                'message' => 'Logout successfully',
            ]);
    }
}
