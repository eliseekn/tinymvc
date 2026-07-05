<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Database\Models\User;
use App\Events\UserRegistered\UserRegisteredEvent;
use Core\Event\Event;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;

class AuthenticationTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_unregistered_user_can_not_login(): void
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('/login', $user->getAttributes(['email', 'password']))
            ->assertSessionHasErrors()
            ->assertRedirectedToUrl('/login');
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/login', [
                'email' => $user->getAttributes('email'),
                'password' => 'P@ssw0rd',
            ])
            ->assertSessionDoesNotHaveErrors()
            ->assertSessionHas('user', $user->getAttributes());
    }

    public function test_user_can_register(): void
    {
        Event::fake(UserRegisteredEvent::class);

        $user = User::factory()->make(['password' => 'P@ssw0rd']);

        $this
            ->post('/register', $user->getAttributes())
            ->assertSessionDoesNotHaveErrors()
            ->assertDatabaseHas('users', $user->getAttributes(['name', 'email']));

        Event::assertDispatched(UserRegisteredEvent::class);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->getAttributes('email'),
            'password' => 'P@ssw0rd',
        ]);

        $this
            ->auth($user)
            ->post('/logout')
            ->assertRedirectedToUrl(config('app.home'))
            ->assertSessionDoesNotHave('user', $user->getAttributes());
    }

    public function test_user_can_not_register_twice(): void
    {
        $user = User::factory()->create(['password' => 'P@ssw0rd']);

        $this
            ->post('/register', $user->getAttributes(['name', 'email', 'password']))
            ->assertSessionHasErrors();
    }
}
