<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Events\UserRegistered\UserRegisteredEvent;
use Core\Event\Event;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;
use Tests\Fixtures;

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
        $user = Fixtures::makeUser(['password' => 'password']);

        $this->post('/login', [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ])
            ->assertSessionHasErrors()
            ->assertRedirectedToUrl('/login');
    }

    public function test_user_can_login(): void
    {
        $user = Fixtures::createUser();

        $this->post('/login', [
            'email' => $user->getEmail(),
            'password' => 'P@ssw0rd',
        ])
            ->assertSessionDoesNotHaveErrors()
            ->assertSessionHas('user', $user->toArray());
    }

    public function test_user_can_register(): void
    {
        Event::fake(UserRegisteredEvent::class);

        $user = Fixtures::makeUser(['password' => 'P@ssw0rd']);

        $this->post('/register', [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ])
            ->assertSessionDoesNotHaveErrors()
            ->assertDatabaseHas('users', ['email' => $user->getEmail()]);

        Event::assertDispatched(UserRegisteredEvent::class);
    }

    public function test_user_can_logout(): void
    {
        $user = Fixtures::createUser();

        $this->post('/login', [
            'email' => $user->getEmail(),
            'password' => 'P@ssw0rd',
        ]);

        $this->auth($user)
            ->post('/logout')
            ->assertRedirectedToUrl(config('app.home'))
            ->assertSessionDoesNotHave('user', $user->toArray());
    }

    public function test_user_can_not_register_twice(): void
    {
        $user = Fixtures::createUser(['password' => 'P@ssw0rd']);

        $this->post('/register', [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ])
            ->assertSessionHasErrors();
    }
}
