<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Factories\UserFactory;
use App\Database\Models\User;
use Core\Testing\ApplicationTestCase;
use Core\Testing\Concerns\RefreshDatabase;

class AuthenticationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_not_authenticate_with_unregistered_user_credentials()
    {
        $user = User::factory(UserFactory::class)->make(['password' => 'password']);

        $client = $this->post('authenticate', $user->toArray('email', 'password'));
        $client->assertSessionHasErrors();
        $client->assertRedirectedToUrl(url('login'));
    }

    public function test_can_authenticate_with_registered_user_credentials()
    {
        $user = User::factory(UserFactory::class)->create();

        $client = $this->post('authenticate', ['email' => $user->email, 'password' => 'password']);
        $client->assertSessionDoesNotHaveErrors();
        $client->assertSessionHas('user', $user->toArray());
    }

    public function test_can_register_user()
    {
        $user = User::factory(UserFactory::class)->make(['password' => 'password']);

        $client = $this->post('register', $user->toArray());
        $client->assertSessionDoesNotHaveErrors();
        $client->assertRedirectedToUrl(url('login'));

        $this->assertDatabaseHas('users', $user->toArray('name', 'email'));
    }

    public function test_can_logout()
    {
        $user = User::factory(UserFactory::class)->create();

        $this->post('authenticate', ['email' => $user->email, 'password' => 'password']);

        $client = $this->actingAs($user)->post('logout');
        $client->assertRedirectedToUrl(url('/'));
        $client->assertSessionDoesNotHave('user', $user->toArray());
    }

    public function test_can_not_register_same_user_twice()
    {
        $user = User::factory(UserFactory::class)->create();

        $client = $this->post('register', $user->toArray());
        $client->assertSessionHasErrors();
    }
}
