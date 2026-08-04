<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Database\Models\UserModel;
use App\Enums\TokenDescription;
use App\Notifications\Mails\PasswordResetMail;
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

    public function test_can_view_forgot_password_page(): void
    {
        $this
            ->get('/password/forgot')
            ->assertHttpStatusOk();
    }

    public function test_can_notify(): void
    {
        $user = Fixtures::createUser();

        Notification::fake(PasswordResetMail::class, $user->getEmail());

        $this
            ->post('/password/notify', ['email' => $user->getEmail()])
            ->assertHttpStatusFound();

        Notification::assertSent(PasswordResetMail::class, $user->getEmail());
    }

    public function test_can_reset_password(): void
    {
        $user = Fixtures::createUser();

        $token = Fixtures::createToken([
            'identifier' => $user->getEmail(),
            'description' => TokenDescription::PASSWORD_RESET->value,
        ]);

        $this->get('/password/reset?email='.$user->getEmail().'&token='.$token->getValue())
            ->assertHttpStatusOk()
            ->assertDatabaseHas('tokens', [
                'identifier' => $token->getIdentifier(),
                'value' => $token->getValue(),
            ]);
    }

    public function test_can_update_password(): void
    {
        $user = Fixtures::createUser();

        $this->post('/password/update', [
            'email' => $user->getEmail(),
            'password' => 'new_P@ssw0rd',
        ])
            ->assertRedirectedToUrl('/login');

        $user = UserModel::find((int) $user->getId());

        $this->assertTrue(Encryption::check('new_P@ssw0rd', $user->getPassword()));
    }
}
