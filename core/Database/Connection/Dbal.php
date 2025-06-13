<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Connection;

use Core\Database\DB;
use Core\Enums\DatabaseDriver;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Types\Type;

final class Dbal
{
    protected Connection $connection;

    public function __construct(DB $db)
    {
        $config = new Configuration;
        $params = [];

        if ($db->driver === DatabaseDriver::SQLITE) {
            $params = [
                'path' => $db->name,
                'driver' => 'sqlite3',
            ];
        } else {
            $params = [
                'dbname' => $db->name,
                'user' => $db->username,
                'password' => $db->password,
                'host' => $db->host,
                'port' => $db->port,
                'driver' => 'pdo_'.$db->driver,
            ];
        }

        $this->connection = DriverManager::getConnection($params, $config);
    }

    public function changeColumn(string $table, string $column, array $definition): void
    {
        $schemaManager = $this->connection->createSchemaManager();
        $platform = $this->connection->getDatabasePlatform();

        $currentSchema = $schemaManager->introspectSchema();
        $newSchema = clone $currentSchema;

        $table = $newSchema->getTable($table);
        $options = [];

        if (isset($definition['size'])) {
            $options['length'] = $definition['size'];
        }

        if (isset($definition['null'])) {
            $options['notnull'] = ! (bool) $definition['null'];
        }

        if (isset($definition['default'])) {
            $options['default'] = $definition['default'];
        }

        if (isset($definition['type'])) {
            $options['type'] = Type::getType($definition['type']);
        }

        $table->modifyColumn($column, $options);

        $comparator = new Comparator($platform);
        $diff = $comparator->compareSchemas($currentSchema, $newSchema);

        foreach ($diff->getAlteredTables() as $tableDiff) {
            foreach ($platform->getAlterTableSQL($tableDiff) as $sql) {
                $this->connection->executeStatement($sql);
            }
        }
    }
}
