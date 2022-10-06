<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Models\User;
use Core\Testing\ApplicationTestCase;
use Core\Testing\Concerns\RefreshDatabase;

class AuthenticationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_not_authenticate_with_unregistered_user_credentials()
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('authenticate', $user->toArray('email', 'password'))
            ->assertSessionHasErrors()
            ->assertRedirectedToUrl(url('login'));
    }

    public function test_can_authenticate_with_registered_user_credentials()
    {
        $user = User::factory()->create();

        $this
            ->post('authenticate', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionDoesNotHaveErrors()
            ->assertSessionHas('user', $user->toArray());
    }

    public function test_can_register_user()
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('register', $user->toArray())
            ->assertSessionDoesNotHaveErrors()
            ->assertRedirectedToUrl(url('login'))
            ->assertDatabaseHas('users', $user->toArray('name', 'email'));
    }

    public function test_can_logout()
    {
        $user = User::factory()->create();

        $this
            ->auth($user)
            ->post('logout')
            ->assertRedirectedToUrl(url('/'))
            ->assertSessionDoesNotHave('user', $user->toArray());
    }

    public function test_can_not_register_same_user_twice()
    {
        $user = User::factory()->create();

        $this
            ->post('register', $user->toArray())
            ->assertSessionHasErrors();
    }
}
