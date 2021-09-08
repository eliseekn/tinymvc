<?php

use App\Database\Models\User;
use Core\Testing\ApplicationTestCase;
use Core\Testing\Traits\RefreshDatabase;

class AuthenticationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function testCanNotAuthenticateWithUnregisteredUserCredentials()
    {
        $user = User::factory()->make();

        $response = $this->post('authenticate', ['email' => $user->email, 'password' => 'password']);
        $response->assertSessionHasErrors();
        $response->assertRedirectedToUrl(url('login'));
    }

    public function testCanAuthenticateWithRegisteredUserCredentials()
    {
        $user = User::factory()->create();

        $response = $this->post('authenticate', ['email' => $user->email, 'password' => 'password']);
        $response->assertSessionDoesNotHaveErrors();
        $response->assertSessionHas('user', $user->toArray());
    }

    public function testCanRegisterUser()
    {
        $user = User::factory()->make(['password' => 'password']);

        $response = $this->post('register', $user->toArray());
        $response->assertSessionDoesNotHaveErrors();
        $response->assertRedirectedToUrl(url('login'));

        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }
}
