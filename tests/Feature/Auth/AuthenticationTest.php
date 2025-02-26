<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Database\Models\User;
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

    public function test_unregistered_user_can_not_authenticate(): void
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('/authenticate', $user->get(['email', 'password']))
            ->assertSessionHasErrors()
            ->assertRedirectedToUrl('/login');
    }

    public function test_user_can_authenticate(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/authenticate', [
                'email' => $user->get('email'),
                'password' => 'password',
            ])
            ->assertSessionDoesNotHaveErrors()
            ->assertSessionHas('user', $user->get());
    }

    public function test_user_can_register(): void
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('/register', $user->get())
            ->assertSessionDoesNotHaveErrors()
            ->assertDatabaseHas('users', $user->get(['name', 'email']));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->post('/authenticate', [
            'email' => $user->get('email'),
            'password' => 'password',
        ]);

        $this
            ->auth($user)
            ->post('/logout')
            ->assertRedirectedToUrl(config('app.home'))
            ->assertSessionDoesNotHave('user', $user->get());
    }

    public function test_user_can_not_register_twice(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/register', $user->get())
            ->assertSessionHasErrors();
    }
}
