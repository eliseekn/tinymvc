<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Models\Token;
use Core\Support\Encryption;
use App\Database\Models\User;
use Core\Testing\ApplicationTestCase;
use Core\Testing\Concerns\RefreshDatabase;

class PasswordForgotTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_reset_password()
    {
        $user = User::factory()->create();
        $token = Token::factory()->create(['email' => $user->email]);

        $this
            ->get("password/reset?email={$token->email}&token={$token->token}")
            ->assertRedirectedToUrl(url("password/new?email={$token->email}"))
            ->assertDatabaseDoesNotHave('tokens', $token->toArray());
    }

    public function test_can_update_password()
    {
        $user = User::factory()->create();

        $this
            ->post('password/update', ['email' => $user->email, 'password' => 'new_password'])
            ->assertRedirectedToUrl(url('login'));

        $user = User::find($user->id);

        $this->assertTrue(Encryption::check('new_password', $user->password));
    }
}
