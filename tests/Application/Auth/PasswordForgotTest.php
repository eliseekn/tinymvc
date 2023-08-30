<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Tests\Application\Auth;

use App\Database\Models\Token;
use App\Database\Models\User;
use App\Enums\TokenDescription;
use Core\Support\Encryption;
use Core\Testing\ApplicationTestCase;
use Core\Testing\RefreshDatabase;

class PasswordForgotTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_reset_password(): void
    {
        $user = User::factory()->create();
        $token = Token::factory()->create([
            'email' => $user->attribute('email'),
            'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
        ]);

        $this->get('/password/reset?email=' . $user->attribute('email') . '&token=' . $token->attribute('value'));
        $this->assertDatabaseDoesNotHave('tokens', $token->toArray());
    }

    public function test_can_update_password(): void
    {
        $user = User::factory()->create();
        $client = $this->post('/password/update', [
            'email' => $user->attribute('email'),
            'password' => 'new_password'
        ]);

        $client->assertRedirectedToUrl(url('login'));
        $this->assertTrue(Encryption::check('new_password', hash_pwd('new_password')));
    }
}
