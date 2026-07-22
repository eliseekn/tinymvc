<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Database\Entities\Token;
use App\Database\Entities\User;
use App\Database\Models\TokenModel;
use App\Database\Models\UserModel;
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
        $user = UserModel::factory()->create()->toEntity(User::class);

        $token = TokenModel::factory()->create([
            'identifier' => $user->getEmail(),
            'description' => TokenDescription::PASSWORD_RESET->value,
        ])->toEntity(Token::class);

        $this
            ->get('/password/reset?email='.$user->getEmail().'&token='.$token->getValue())
            ->assertStatusOk()
            ->assertDatabaseDoesNotHave('tokens', [
                'identifier' => $token->getIdentifier(),
                'value' => $token->getValue(),
            ]);
    }

    public function test_can_update_password(): void
    {
        $user = UserModel::factory()->create()->toEntity(User::class);

        $this
            ->post('/password/update', [
                'email' => $user->getEmail(),
                'password' => 'new_P@ssw0rd',
            ])
            ->assertRedirectedToUrl('/login')
            ->assertTrue(Encryption::check('new_P@ssw0rd', UserModel::find((int) $user->getId())?->getPassword()));
    }
}
