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
        if (! config('security.auth.email_verification')) {
            $this->markTestSkipped('Email verification has not been enabled.');

            return;
        }

        $user = User::factory()->make(['password' => 'P@ssw0rd']);

        Event::fake(UserRegisteredEvent::class);
        Notification::fake(VerificationMail::class, $user->get('email'));

        $this
            ->post('/register', $user->get())
            ->assertSessionDoesNotHaveErrors()
            ->assertDatabaseHas('users', $user->get(['name', 'email']));

        Event::assertDispatched(UserRegisteredEvent::class);
        Notification::assertSent(VerificationMail::class, $user->get('email'));
    }

    public function test_can_verify_email(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $token = Token::factory()->create([
            'identifier' => $user->get('email'),
            'description' => TokenDescription::EMAIL_VERIFICATION->value,
        ]);

        $this
            ->get('/email/verify?email='.$user->get('email').'&token='.$token->get('value'))
            ->assertRedirectedToUrl('/login')
            ->assertDatabaseDoesNotHave('tokens', $token->get(['identifier', 'value']));
    }
}
