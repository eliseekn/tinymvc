<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Testing\Traits;

use Core\Database\Repository;

trait DatabaseTestCase
{
    public function assertDatabaseHas(string $table, array $expected): self
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertTrue($result);

        return $this;
    }

    public function assertDatabaseDoesNotHave(string $table, array $expected): self
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertFalse($result);

        return $this;
    }

    public function assertDatabaseHasCount(string $table, int $expected, string $column = 'id'): self
    {
        $result = (new Repository($table))->metrics()->count($column)->metrics();
        $this->assertEquals((int) $result, $expected);

        return $this;
    }

    public function assertDatabaseDoesNotHaveCount(string $table, int $expected, string $column = 'id'): self
    {
        $result = (new Repository($table))->metrics()->count($column)->metrics();
        $this->assertEquals((int) $result, $expected);

        return $this;
    }
}
