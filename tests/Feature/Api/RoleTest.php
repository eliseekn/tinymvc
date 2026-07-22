<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Entities\Role;
use App\Database\Models\RoleModel;
use Core\Database\Factory\Factory;
use Core\Testing\FeatureTestCase;
use Core\Testing\Traits\RefreshDatabase;

class RoleTest extends FeatureTestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->refreshDatabase();
    }

    public function test_can_get_collection(): void
    {
        $role = Factory::for(RoleModel::class)->create()->toEntity(Role::class);
        Factory::for(RoleModel::class)->create()->toEntity(Role::class);

        $this
            ->getJson('/api/v1/roles')
            ->assertStatusOk()
            ->assertJsonContains([
                [
                    'name' => $role->getName(),
                ],
            ]);
    }
}
