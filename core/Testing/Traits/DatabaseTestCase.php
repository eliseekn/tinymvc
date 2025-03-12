<?php

declare(strict_types=1);

namespace Core\Testing\Traits;

use Core\Database\Repository;
use Core\Exceptions\InvalidSQLQueryException;

trait DatabaseTestCase
{
    /**
     * @throws InvalidSQLQueryException
     */
    public function assertDatabaseHas(string $table, array $expected): self
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertTrue($result);

        return $this;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function assertDatabaseDoesNotHave(string $table, array $expected): self
    {
        $result = (new Repository($table))->findMany($expected, 'and')->exists();
        $this->assertFalse($result);

        return $this;
    }
}
