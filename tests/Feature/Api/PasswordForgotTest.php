<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Models\UserModel;
use App\Enums\TokenDescription;
use App\Notifications\Mails\PasswordResetMail;
use Core\Enums\HttpCode;
use Core\Notification\Notification;
use Core\Support\Encryption;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

class PasswordForgotTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_notify(): void
    {
        $user = Fixtures::createUser();

        Notification::fake(PasswordResetMail::class, $user->getEmail());

        $this
            ->postJson('/api/v1/password/notify', ['email' => $user->getEmail()])
            ->assertHttpStatusOk();

        Notification::assertSent(PasswordResetMail::class, $user->getEmail());
    }

    public function test_can_reset_password(): void
    {
        $user = Fixtures::createUser();

        $token = Fixtures::createToken([
            'identifier' => $user->getEmail(),
            'description' => TokenDescription::PASSWORD_RESET->value,
        ]);

        $this
            ->getJson('/api/v1/password/reset?email='.$user->getEmail().'&token='.$token->getValue())
            ->assertHttpStatusOk();
    }

    public function test_reset_fails_with_invalid_token(): void
    {
        $user = Fixtures::createUser();

        $this
            ->getJson('/api/v1/password/reset?email='.$user->getEmail().'&token=invalid-token')
            ->assertHttpStatusEquals(HttpCode::BAD_REQUEST);
    }

    public function test_can_update_password(): void
    {
        $user = Fixtures::createUser();

        $this
            ->postJson('/api/v1/password/update', [
                'email' => $user->getEmail(),
                'password' => 'new_P@ssw0rd',
            ])
            ->assertHttpStatusOk();

        $updated = UserModel::find((int) $user->getId());

        $this->assertTrue(Encryption::check('new_P@ssw0rd', $updated->getPassword()));
    }

    public function test_update_fails_for_unknown_email(): void
    {
        $this
            ->postJson('/api/v1/password/update', [
                'email' => faker()->unique()->safeEmail(),
                'password' => 'new_P@ssw0rd',
            ])
            ->assertHttpStatusServerError();
    }
}
