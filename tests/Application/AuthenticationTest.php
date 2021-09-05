<?php

use App\Database\Models\User;
use Core\Testing\ApplicationTestCase;
use Core\Testing\Traits\RefreshDatabase;

class AuthenticationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function testCanNotAuthenticateWithoutCredentials()
    {
        $response = $this->post('authenticate', []);
        $response->assertSessionHasErrors();
    }

    public function testCanNotAuthenticateWithUnregisteredUserCredentials()
    {
        $data['email'] = $this->faker->unique()->email();
        $data['password'] = 'password';
  
        $response = $this->post('authenticate', $data);
        $response->assertSessionHasErrors();
    }

    public function testCanNotAuthenticateWithBadCredentials()
    {
        $user = User::factory()->make([
            'email' => 'test@test.com',
            'password' => hash_pwd('password')
        ]);

        $response = $this->post('authenticate', $user->toArray());
        $response->assertSessionHasErrors();
    }

    public function testCanAuthenticateWithRegisteredUserCredentials()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => hash_pwd('password')
        ]);

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
