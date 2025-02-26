<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Database\Models\Token;
use App\Database\Models\User;
use App\Enums\TokenDescription;
use Core\Support\Encryption;
use Core\Testing\FeatureTestCase;
use Core\Testing\RefreshDatabase;

class PasswordForgotTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->refreshDatabase();

        parent::tearDown();
    }

    public function test_can_reset_password(): void
    {
        $user = User::factory()->create();

        $token = Token::factory()->create([
            'email' => $user->get('email'),
            'description' => TokenDescription::PASSWORD_RESET,
        ]);

        $this
            ->get('/password/reset?email=' . $user->get('email') . '&token=' . $token->get('value'))
            ->assertDatabaseDoesNotHave('tokens', $token->get());
    }

    public function test_can_update_password(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/password/update', [
                'email' => $user->get('email'),
                'password' => 'new_password',
            ])
            ->assertRedirectedToUrl('/login')
            ->assertTrue(Encryption::check('new_password', User::find($user->getId())->get('password')));
    }
}
