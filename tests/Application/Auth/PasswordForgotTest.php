<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Support\Encryption;
use App\Database\Models\User;
use App\Database\Models\Token;
use Core\Testing\ApplicationTestCase;
use App\Database\Factories\UserFactory;
use App\Database\Factories\TokenFactory;
use Core\Testing\Concerns\RefreshDatabase;

class PasswordForgotTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_reset_password()
    {
        $user = User::factory(UserFactory::class)->create();
        $token = Token::factory(TokenFactory::class)->create(['email' => $user->email]);

        $client = $this->get("password/reset?email={$token->email}&token={$token->token}");
        $client->assertRedirectedToUrl(url("password/new?email={$token->email}"));

        $this->assertDatabaseDoesNotHave('tokens', $token->toArray());
    }

    public function test_can_update_password()
    {
        $user = User::factory(UserFactory::class)->create();

        $client = $this->post('password/update', ['email' => $user->email, 'password' => 'new_password']);
        $client->assertRedirectedToUrl(url('login'));

        $user = User::find($user->id);

        $this->assertTrue(Encryption::check('new_password', $user->password));
    }
}
