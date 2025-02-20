<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Tests\Application\Auth;

use App\Database\Models\User;
use Core\Testing\ApplicationTestCase;
use Core\Testing\RefreshDatabase;

class AuthenticationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->refreshDatabase();

        parent::tearDown();
    }

    public function test_can_not_authenticate_with_unregistered_user_credentials(): void
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('/authenticate', $user->get(['email', 'password']))
            ->assertSessionHasErrors()
            ->assertRedirectedToUrl(url('login'));
    }

    public function test_can_authenticate_with_registered_user_credentials(): void
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

    public function test_can_register_user(): void
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('/register', $user->get())
            ->assertSessionDoesNotHaveErrors()
            ->assertRedirectedToUrl(url('/login'))
            ->assertDatabaseHas('users', $user->get(['name', 'email']));
    }

    public function test_can_logout(): void
    {
        $user = User::factory()->create();

        $this->post('/authenticate', [
            'email' => $user->get('email'),
            'password' => 'password',
        ]);

        $this
            ->auth($user)
            ->post('/logout')
            ->assertRedirectedToUrl(url('/'))
            ->assertSessionDoesNotHave('user', $user->get());
    }

    public function test_can_not_register_same_user_twice(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/register', $user->get())
            ->assertSessionHasErrors();
    }
}
