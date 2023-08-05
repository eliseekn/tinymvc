<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database\Connection;

use PDO;
use PDOStatement;

interface ConnectionInterface
{
    public function getPDO(): PDO;

    public function executeStatement(string $query): false|int;

	public function executeQuery(string $query, array $args): false|PDOStatement;

    public function schemaExists(string $name): bool;

    public function tableExists(string $name): bool;

    public function createSchema(string $name): void;

    public function deleteSchema(string $name): void;
}
