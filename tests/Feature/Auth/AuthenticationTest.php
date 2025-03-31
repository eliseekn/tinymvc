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
use App\Database\Seeders\RoleSeeder;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;

class AuthenticationTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        RoleSeeder::run();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_unregistered_user_can_not_login(): void
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('/login', $user->get(['email', 'password']))
            ->assertSessionHasErrors()
            ->assertRedirectedToUrl('/login');
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/login', [
                'email' => $user->get('email'),
                'password' => 'password',
            ])
            ->assertSessionDoesNotHaveErrors()
            ->assertSessionHas('user', $user->get());
    }

    public function test_user_can_register(): void
    {
        $user = User::factory()->make(['password' => 'password']);

        $this
            ->post('/register', $user->get())
            ->assertSessionDoesNotHaveErrors()
            ->assertDatabaseHas('users', $user->get(['name', 'email']));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->get('email'),
            'password' => 'password',
        ]);

        $this
            ->auth($user)
            ->post('/logout')
            ->assertRedirectedToUrl(config('app.home'))
            ->assertSessionDoesNotHave('user', $user->get());
    }

    public function test_user_can_not_register_twice(): void
    {
        $user = User::factory()->create();

        $this
            ->post('/register', $user->get())
            ->assertSessionHasErrors();
    }
}
