<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Tests\Application\Auth;

use App\Enums\TokenDescription;
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

    public function test_can_reset_password(): void
    {
        $user = (new UserFactory())->create();
        $token = (new TokenFactory())->create([
            'email' => $user->attribute('email'),
            'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
        ]);

        $this->get('/password/reset?email=' . $user->attribute('email') . '&token=' . $token->attribute('value'));
        $this->assertDatabaseDoesNotHave('tokens', $token->toArray());
    }

    public function test_can_update_password(): void
    {
        $user = (new UserFactory())->create();
        $client = $this->post('/password/update', [
            'email' => $user->attribute('email'),
            'password' => 'new_password'
        ]);

        $client->assertRedirectedToUrl(url('login'));
        $this->assertTrue(Encryption::check('new_password', hash_pwd('new_password')));
    }
}
