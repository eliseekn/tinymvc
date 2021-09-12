<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database\Connection;

interface ConnectionInterface
{
    public function getPDO();

    public function executeStatement(string $query);

	public function executeQuery(string $query, array $args);

    public function schemaExists(string $name);

    public function tableExists(string $name);

    public function createSchema(string $name);

    public function deleteSchema(string $name);
}
