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

    public function testCanNotAuthenticateWithUnregisteredUserCredentials()
    {
        $response = $this->post('authenticate', ['email' => 'a@b.c', 'password' => 'password']);
        $response->assertSessionHasErrors();
        $response->assertRedirectedToUrl(url('login'));
    }

    public function testCanAuthenticateWithRegisteredUserCredentials()
    {
        $user = User::factory(UserFactory::class)->create();

        $response = $this->post('authenticate', ['email' => $user->email, 'password' => 'password']);
        $response->assertSessionDoesNotHaveErrors();
        $response->assertSessionHas('user', $user->toArray());
    }

    public function testCanRegisterUser()
    {
        $user = User::factory(UserFactory::class)->make(['password' => 'password']);

        $response = $this->post('register', $user->toArray());
        $response->assertSessionDoesNotHaveErrors();
        $response->assertRedirectedToUrl(url('login'));

        $this->assertDatabaseHas('users', $user->toArray('name', 'email'));
    }

    public function testCanNotRegisterSameUserTwice()
    {
        $user = User::factory(UserFactory::class)->make(['password' => 'password']);

        $this->post('register', $user->toArray());

        $response = $this->post('register', $user->toArray());
        $response->assertSessionHasErrors();
        //$response->assertRedirectedToUrl(url('login'));
    }
}
