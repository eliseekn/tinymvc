<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit\Validation\Rules;

use App\Http\Validation\Rules\Unique;
use Core\Testing\Traits\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Validation\Factory;
use Tests\Fixtures;

class UniqueTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    private function fails(array $inputs, array $rules): bool
    {
        $rule = new Unique;

        $factory = new Factory;
        $factory->addRule($rule->name(), $rule);

        $validation = $factory->make($inputs, $rules);
        $validation->validate();

        return $validation->fails();
    }

    public function test_passes_when_value_is_not_taken(): void
    {
        $this->assertFalse($this->fails(
            ['email' => faker()->unique()->safeEmail()],
            ['email' => 'unique:users']
        ));
    }

    public function test_fails_when_value_is_already_taken(): void
    {
        $user = Fixtures::createUser();

        $this->assertTrue($this->fails(
            ['email' => $user->getEmail()],
            ['email' => 'unique:users']
        ));
    }

    public function test_passes_when_excluding_own_record(): void
    {
        $user = Fixtures::createUser();

        $this->assertFalse($this->fails(
            ['email' => $user->getEmail()],
            ['email' => 'unique:users,id,'.$user->getId()]
        ));
    }

    public function test_fails_when_value_is_taken_by_another_record(): void
    {
        $user = Fixtures::createUser();
        $otherUser = Fixtures::createUser();

        $this->assertTrue($this->fails(
            ['email' => $otherUser->getEmail()],
            ['email' => 'unique:users,id,'.$user->getId()]
        ));
    }
}
