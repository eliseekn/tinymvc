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
use Core\Testing\ApplicationTestCase;
use Core\Testing\RefreshDatabase;

class EmailVerificationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_verify_email(): void
    {
        $user = User::factory()->create(['email_verified' => null]);
        $token = Token::factory()->create([
            'email' => $user->attribute('email'),
            'description' => TokenDescription::EMAIL_VERIFICATION_TOKEN->value
        ]);

        $client = $this->get('/email/verify?email=' . $user->attribute('email') . '&token=' . $token->attribute('value'));
        $client->assertRedirectedToUrl(url('login'));
        $this->assertDatabaseDoesNotHave('tokens', $token->toArray());
    }
}
