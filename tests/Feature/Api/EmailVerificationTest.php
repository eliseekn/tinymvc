<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Entities\Token;
use App\Database\Entities\User;
use App\Database\Models\TokenModel;
use App\Database\Models\UserModel;
use App\Enums\TokenDescription;
use App\Notifications\Mails\VerificationMail;
use Core\Enums\HttpCode;
use Core\Notification\Notification;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;

class EmailVerificationTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_notify(): void
    {
        $user = UserModel::factory()->create()->toEntity(User::class);

        Notification::fake(VerificationMail::class, $user->getEmail());

        $this
            ->postJson('/api/v1/email/notify', ['email' => $user->getEmail()])
            ->assertHttpStatusOk();

        Notification::assertSent(VerificationMail::class, $user->getEmail());
    }

    public function test_can_verify(): void
    {
        $user = UserModel::factory()->create(['email_verified_at' => null])->toEntity(User::class);

        $token = TokenModel::factory()->create([
            'identifier' => $user->getEmail(),
            'description' => TokenDescription::EMAIL_VERIFICATION->value,
        ])->toEntity(Token::class);

        $this
            ->getJson('/api/v1/email/verify?email='.$user->getEmail().'&token='.$token->getValue())
            ->assertHttpStatusOk()
            ->assertDatabaseDoesNotHave('tokens', [
                'identifier' => $token->getIdentifier(),
                'value' => $token->getValue(),
            ]);
    }

    public function test_verify_fails_with_invalid_token(): void
    {
        $user = UserModel::factory()->create(['email_verified_at' => null])->toEntity(User::class);

        $this
            ->getJson('/api/v1/email/verify?email='.$user->getEmail().'&token=invalid-token')
            ->assertHttpStatusEquals(HttpCode::BAD_REQUEST);
    }
}
