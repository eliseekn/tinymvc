<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Tests\Feature\Auth;

use App\Database\Models\Token;
use App\Database\Models\User;
use App\Enums\TokenDescription;
use Core\Testing\FeatureTestCase;
use Core\Testing\RefreshDatabase;

class EmailVerificationTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->refreshDatabase();

        parent::tearDown();
    }

    public function test_can_verify_email(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $token = Token::factory()->create([
            'email' => $user->get('email'),
            'description' => TokenDescription::EMAIL_VERIFICATION,
        ]);

        $this
            ->get('/email/verify?email=' . $user->get('email') . '&token=' . $token->get('value'))
            ->assertRedirectedToUrl('/login')
            ->assertDatabaseDoesNotHave('tokens', $token->get());
    }
}
