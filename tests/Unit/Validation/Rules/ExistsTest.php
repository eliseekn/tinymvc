<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit\Validation\Rules;

use App\Database\Entities\Role;
use App\Database\Models\RoleModel;
use App\Http\Validation\Rules\Exists;
use Core\Testing\Traits\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\Validation\Factory;

class ExistsTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    private function fails(array $inputs, array $rules): bool
    {
        $rule = new Exists;

        $factory = new Factory;
        $factory->addRule($rule->name(), $rule);

        $validation = $factory->make($inputs, $rules);
        $validation->validate();

        return $validation->fails();
    }

    public function test_passes_when_row_exists(): void
    {
        $role = RoleModel::factory()->create()->toEntity(Role::class);

        $this->assertFalse($this->fails(
            ['role_id' => $role->getId()],
            ['role_id' => 'exists:roles,id']
        ));
    }

    public function test_fails_when_row_does_not_exist(): void
    {
        $this->assertTrue($this->fails(
            ['role_id' => 999999],
            ['role_id' => 'exists:roles,id']
        ));
    }
}
