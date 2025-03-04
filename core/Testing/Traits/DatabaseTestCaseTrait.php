<?php

declare(strict_types=1);

namespace Core\Testing\Traits;

use Core\Database\Repository;

trait DatabaseTestCaseTrait
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
}
