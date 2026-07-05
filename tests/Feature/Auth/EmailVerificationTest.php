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
use App\Events\UserRegistered\UserRegisteredEvent;
use App\Notifications\Mails\VerificationMail;
use Core\Event\Event;
use Core\Notification\Notification;
use Core\Support\Config;
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

    public function test_can_send_vetification_email(): void
    {
        $emailVerification = config('security.auth.email_verification');

        Config::updateEnv(['AUTH_EMAIL_VERIFICATION' => true]);

        $user = User::factory()->make(['password' => 'P@ssw0rd']);

        Event::fake(UserRegisteredEvent::class);
        Notification::fake(VerificationMail::class, $user->getAttributes('email'));

        $this
            ->post('/register', $user->getAttributes())
            ->assertSessionDoesNotHaveErrors()
            ->assertDatabaseHas('users', $user->getAttributes(['name', 'email']));

        Event::assertDispatched(UserRegisteredEvent::class);
        Notification::assertSent(VerificationMail::class, $user->getAttributes('email'));

        Config::updateEnv(['AUTH_EMAIL_VERIFICATION' => $emailVerification]);
    }

    public function test_can_verify_email(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $token = Token::factory()->create([
            'identifier' => $user->getAttributes('email'),
            'description' => TokenDescription::EMAIL_VERIFICATION->value,
        ]);

        $this
            ->get('/email/verify?email='.$user->getAttributes('email').'&token='.$token->getAttributes('value'))
            ->assertRedirectedToUrl('/login')
            ->assertDatabaseDoesNotHave('tokens', $token->getAttributes(['identifier', 'value']));
    }
}
