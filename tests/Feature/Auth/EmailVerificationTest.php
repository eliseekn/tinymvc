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

        $user = UserModel::factory()->make(['password' => 'P@ssw0rd'])->toEntity(User::class);

        Event::fake(UserRegisteredEvent::class);
        Notification::fake(VerificationMail::class, $user->getEmail());

        $this
            ->post('/register', $user->toArray())
            ->assertSessionDoesNotHaveErrors()
            ->assertDatabaseHas('users', [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]);

        Event::assertDispatched(UserRegisteredEvent::class);
        Notification::assertSent(VerificationMail::class, $user->getEmail());

        Config::updateEnv(['AUTH_EMAIL_VERIFICATION' => $emailVerification]);
    }

    public function test_can_verify_email(): void
    {
        $user = UserModel::factory()->create(['email_verified_at' => null])->toEntity(User::class);

        $token = TokenModel::factory()->create([
            'identifier' => $user->getEmail(),
            'description' => TokenDescription::EMAIL_VERIFICATION->value,
        ])->toEntity(Token::class);

        $this
            ->get('/email/verify?email='.$user->getEmail().'&token='.$token->getValue())
            ->assertRedirectedToUrl('/login')
            ->assertDatabaseDoesNotHave('tokens', [
                'identifier' => $token->getIdentifier(),
                'value' => $token->getValue(),
            ]);
    }
}
