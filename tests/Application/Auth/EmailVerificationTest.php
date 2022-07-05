<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Models\Token;
use App\Database\Models\User;
use Core\Testing\ApplicationTestCase;
use Core\Testing\Concerns\RefreshDatabase;

class EmailVerificationTest extends ApplicationTestCase
{
    use RefreshDatabase;

    public function test_can_verify_email()
    {
        $user = User::factory()->create(['email_verified' => null]);
        $token = Token::factory()->create(['email' => $user->email]);

        $this
            ->get("email/verify?email={$token->email}&token={$token->token}")
            ->assertRedirectedToUrl(url('login'))
            ->assertDatabaseDoesNotHave('tokens', $token->toArray());
    }
}
