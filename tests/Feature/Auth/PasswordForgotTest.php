<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Database\Models\Token;
use App\Database\Models\User;
use App\Enums\TokenDescription;
use Core\Support\Encryption;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;

class PasswordForgotTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_reset_password(): void
    {
        $user = User::factory()->create();

        $token = Token::factory()->create([
            'identifier' => $user->getAttributes('email'),
            'description' => TokenDescription::PASSWORD_RESET->value,
        ]);

        $this
            ->get('/password/reset?email='.$user->getAttributes('email').'&token='.$token->getAttributes('value'))
            ->assertStatusOk()
            ->assertDatabaseDoesNotHave('tokens', $token->getAttributes(['identifier', 'value']));
    }

    public function test_can_update_password(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/password/update', [
                'email' => $user->getAttributes('email'),
                'password' => 'new_P@ssw0rd',
            ])
            ->assertRedirectedToUrl('/login')
            ->assertTrue(Encryption::check('new_P@ssw0rd', User::find($user->getId())->getAttributes('password')));
    }
}
