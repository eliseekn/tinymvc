<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Factories\UserFactory;
use Core\Testing\ApplicationTestCase;
use Core\Testing\Concerns\RefreshDatabase;

class AuthenticationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_not_authenticate_with_unregistered_user_credentials()
    {
        $user = (new UserFactory())->make(['password' => 'password']);

        $client = $this->post('authenticate', $user->toArray('email', 'password'));
        $client->assertSessionHasErrors();
        $client->assertRedirectedToUrl(url('login'));
    }

    public function test_can_authenticate_with_registered_user_credentials()
    {
        $user = (new UserFactory())->create();

        $client = $this->post('authenticate', ['email' => $user->email, 'password' => 'password']);
        $client->assertSessionDoesNotHaveErrors();
        $client->assertSessionHas('user', $user->toArray());
    }

    public function test_can_register_user()
    {
        $user = (new UserFactory())->make(['password' => 'password']);

        $client = $this->post('register', $user->toArray());
        $client->assertSessionDoesNotHaveErrors();
        $client->assertRedirectedToUrl(url('login'));

        $this->assertDatabaseHas('users', $user->toArray('name', 'email'));
    }

    public function test_can_logout()
    {
        $user = (new UserFactory())->create();

        $this->post('authenticate', ['email' => $user->email, 'password' => 'password']);

        $client = $this->auth($user)->post('logout');
        $client->assertRedirectedToUrl(url('/'));
        $client->assertSessionDoesNotHave('user', $user->toArray());
    }

    public function test_can_not_register_same_user_twice()
    {
        $user = (new UserFactory())->create();

        $client = $this->post('register', $user->toArray());
        $client->assertSessionHasErrors();
    }
}
