<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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

    public function test_can_not_authenticate_with_unregistered_user_credentials(): void
    {
        $user = User::factory()->make(['password' => 'password']);
        $client = $this->post('/authenticate', $user->getAttribute(['email', 'password']));
        $client->assertSessionHasErrors();
        $client->assertRedirectedToUrl(url('login'));
    }

    public function test_can_authenticate_with_registered_user_credentials(): void
    {
        $user = User::factory()->create();
        $client = $this->post('/authenticate', [
            'email' => $user->getAttribute('email'),
            'password' => 'password'
        ]);
        
        $client->assertSessionDoesNotHaveErrors();
        $client->assertSessionHas('user', $user->getAttribute());
    }

    public function test_can_register_user(): void
    {
        $user = User::factory()->make(['password' => 'password']);
        $client = $this->post('/register', $user->getAttribute());
        $client->assertSessionDoesNotHaveErrors();

        if (!config('security.auth.email_verification')) {
            $client->assertRedirectedToUrl(url('/login'));
        } else {
            $client->assertRedirectedToUrl(url('/email/notify?email=' . $user->getAttribute('email')));
        }

        $this->assertDatabaseHas('users', $user->getAttribute(['name', 'email']));
    }

    public function test_can_logout(): void
    {
        $user = User::factory()->create();
        $this->post('/authenticate', [
            'email' => $user->getAttribute('email'),
            'password' => 'password'
        ]);

        $client = $this->auth($user)->post('/logout');
        $client->assertRedirectedToUrl(url('/'));
        $client->assertSessionDoesNotHave('user', $user->getAttribute());
    }

    public function test_can_not_register_same_user_twice(): void
    {
        $user = User::factory()->create();
        $client = $this->post('/register', $user->getAttribute());
        $client->assertSessionHasErrors();
    }
}
