<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Database\Models\Role;
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
        $roles = Role::factory(10)->create();

        $this
            ->getJson('/api/v1/roles')
            ->assertStatusOk()
            ->assertJsonContains([
                [
                    'name' => $roles[0]->get('name'),
                ],
            ]);
    }
}
