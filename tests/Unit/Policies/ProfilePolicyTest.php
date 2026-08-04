<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Database\Entities\User;
use App\Policies\ProfilePolicy;
use Core\Http\Auth;
use Core\Testing\Traits\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures;

class ProfilePolicyTest extends TestCase
{
    use RefreshDatabase;

    private ProfilePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new ProfilePolicy;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Auth::forget();
        $this->refreshDatabase();
    }

    private function actingAs(User $user): void
    {
        session()->create('user', $user->toArray());
    }

    public function test_index_create_store_edit_and_show_are_always_allowed(): void
    {
        $user = Fixtures::createUser();

        $this->assertTrue($this->policy->index());
        $this->assertTrue($this->policy->create());
        $this->assertTrue($this->policy->store());
        $this->assertTrue($this->policy->edit($user->toModel()));
        $this->assertTrue($this->policy->show($user->toModel()));
    }

    public function test_admin_can_update_any_profile(): void
    {
        $admin = Fixtures::createAdmin();
        $otherUser = Fixtures::createUser();

        $this->actingAs($admin);

        $this->assertTrue($this->policy->update($otherUser->toModel()));
    }

    public function test_user_can_update_own_profile(): void
    {
        $user = Fixtures::createUser();

        $this->actingAs($user);

        $this->assertTrue($this->policy->update($user->toModel()));
    }

    public function test_user_can_not_update_another_users_profile(): void
    {
        $user = Fixtures::createUser();
        $otherUser = Fixtures::createUser();

        $this->actingAs($user);

        $this->assertFalse($this->policy->update($otherUser->toModel()));
    }

    public function test_user_can_delete_own_profile(): void
    {
        $user = Fixtures::createUser();

        $this->actingAs($user);

        $this->assertTrue($this->policy->delete($user->toModel()));
    }

    public function test_admin_can_not_delete_another_users_profile(): void
    {
        $admin = Fixtures::createAdmin();
        $otherUser = Fixtures::createUser();

        $this->actingAs($admin);

        $this->assertFalse($this->policy->delete($otherUser->toModel()));
    }
}
