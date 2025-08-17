<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database;

use Core\Database\Connection\Connection;
use Core\Database\Connection\Dbal;
use Core\Enums\DatabaseDriver;
use PDOStatement;

/**
 * Manage schemes
 */
class Schema
{
    protected static QueryBuilder $qb;

    protected static bool $alterMode;

    public static function createTable(string $name): self
    {
        static::$qb = QueryBuilder::createTable($name);
        static::$alterMode = false;

        return new self;
    }

    public static function alterTable(string $table): self
    {
        static::$qb = QueryBuilder::alter($table);
        static::$alterMode = true;

        return new self;
    }

    public function renameColumn(string $old, string $new): self
    {
        static::$qb->renameColumn($old, $new);

        return $this;
    }

    public static function changeColumn(string $table, string $column, array $attributess = []): void
    {
        (new Dbal(Connection::getInstance()->getDB()))->changeColumn($table, $column, $attributess);
    }

    public function dropColumn(string $column): self
    {
        static::$qb->dropColumn($column);

        return $this;
    }

    public static function dropTable(string $table): void
    {
        QueryBuilder::dropTable($table)->execute();
    }

    public static function dropForeign(string $name): false|PDOStatement
    {
        return QueryBuilder::dropForeign('fk_'.$name)->execute();
    }

    public static function disableForeignKeyCheck(): false|PDOStatement
    {
        $query = match (Connection::getInstance()->getDriver()) {
            DatabaseDriver::MYSQL => 'SET foreign_key_checks = 0',
            DatabaseDriver::PGSQL => 'SET session_replication_role = replica',
            default => 'PRAGMA foreign_keys = OFF',
        };

        return QueryBuilder::setQuery($query)->execute();
    }

    public static function enableForeignKeyCheck(): false|PDOStatement
    {
        $query = match (Connection::getInstance()->getDriver()) {
            DatabaseDriver::MYSQL => 'SET foreign_key_checks = 1',
            DatabaseDriver::PGSQL => 'SET session_replication_role = origin',
            default => 'PRAGMA foreign_keys = ON',
        };

        return QueryBuilder::setQuery($query)->execute();
    }

    public function after(string $column): self
    {
        static::$qb->after($column);

        return $this;
    }

    public function first(): self
    {
        static::$qb->first();

        return $this;
    }

    public function addReal(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'REAL');

        return $this;
    }

    private function addInteger(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'INTEGER');

        return $this;
    }

    public function addInt(string $name, int $size = 11, bool $unsigned = false): self
    {
        if (Connection::getInstance()->getDriver() !== DatabaseDriver::MYSQL) {
            return $this->addInteger($name);
        }

        static::$qb->column($this->getColumnName($name), "INT($size)".($unsigned ? ' UNSIGNED' : ''));

        return $this;
    }

    public function addTinyInt(string $name, int $size = 4, bool $unsigned = false): self
    {
        if (Connection::getInstance()->getDriver() === DatabaseDriver::PGSQL) {
            static::$qb->column($this->getColumnName($name), 'SMALLINT');
        } elseif (Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE) {
            static::$qb->column($this->getColumnName($name), 'INTEGER');
        } else {
            static::$qb->column($this->getColumnName($name), "TINYINT($size)".($unsigned ? ' UNSIGNED' : ''));
        }

        return $this;
    }

    public function addSmallInt(string $name, int $size = 6, bool $unsigned = false): self
    {
        if (Connection::getInstance()->getDriver() === DatabaseDriver::PGSQL) {
            static::$qb->column($this->getColumnName($name), 'SMALLINT');
        } elseif (Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE) {
            static::$qb->column($this->getColumnName($name), 'INTEGER');
        } else {
            static::$qb->column($this->getColumnName($name), "SMALLINT($size)".($unsigned ? ' UNSIGNED' : ''));
        }

        return $this;
    }

    public function addMediumInt(string $name, int $size = 8, bool $unsigned = false): self
    {
        if (Connection::getInstance()->getDriver() !== DatabaseDriver::MYSQL) {
            return $this->addInteger($name);
        }

        static::$qb->column($this->getColumnName($name), "MEDIUMINT($size)".($unsigned ? ' UNSIGNED' : ''));

        return $this;
    }

    public function addBigInt(string $name, int $size = 20, bool $unsigned = false): self
    {
        if (Connection::getInstance()->getDriver() === DatabaseDriver::PGSQL) {
            static::$qb->column($this->getColumnName($name), 'BIGINT');
        } elseif (Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE) {
            static::$qb->column($this->getColumnName($name), 'INTEGER');
        } else {
            static::$qb->column($this->getColumnName($name), "BIGINT($size)".($unsigned ? ' UNSIGNED' : ''));
        }

        return $this;
    }

    public function addSerial(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'SERIAL');

        return $this;
    }

    public function addBigSerial(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'BIGSERIAL');

        return $this;
    }

    public function addFloat(string $name, int $size = 10, int $precision = 2): self
    {
        if (Connection::getInstance()->getDriver() === DatabaseDriver::PGSQL) {
            static::$qb->column($this->getColumnName($name), "NUMERIC($size, $precision)");
        } elseif (Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE) {
            static::$qb->column($this->getColumnName($name), 'REAL');
        } else {
            static::$qb->column($this->getColumnName($name), "FLOAT($size, $precision)");
        }

        return $this;
    }

    public function addDouble(string $name, int $size = 10, int $precision = 2): self
    {
        static::$qb->column($this->getColumnName($name), "DOUBLE($size, $precision)");

        return $this;
    }

    public function addDecimal(string $name, int $size = 10, int $precision = 2): self
    {
        static::$qb->column($this->getColumnName($name), "DECIMAL($size, $precision)");

        return $this;
    }

    public function addChar(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'CHAR(1)');

        return $this;
    }

    public function addVarChar(string $name, int $size = 255): self
    {
        static::$qb->column($this->getColumnName($name), "VARCHAR($size)");

        return $this;
    }

    public function addText(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'TEXT');

        return $this;
    }

    public function addTinyText(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'TINYTEXT');

        return $this;
    }

    public function addMediumText(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'MEDIUMTEXT');

        return $this;
    }

    public function addLongText(string $name): self
    {
        if (Connection::getInstance()->getDriver() !== DatabaseDriver::MYSQL) {
            static::$qb->column($this->getColumnName($name), 'TEXT');

            return $this;
        }

        static::$qb->column($this->getColumnName($name), 'LONGTEXT');

        return $this;
    }

    public function addBlob(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'BLOB');

        return $this;
    }

    public function addTinyBlob(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'TINYBLOB');

        return $this;
    }

    public function addMediumBlob(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'MEDIUMBLOB');

        return $this;
    }

    public function addLongBlob(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'LONGBLOB');

        return $this;
    }

    public function addDate(string $name): self
    {
        if (Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE) {
            return $this->addText($name);
        }

        static::$qb->column($this->getColumnName($name), 'DATE');

        return $this;
    }

    public function addTime(string $name): self
    {
        if (Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE) {
            return $this->addText($name);
        }

        static::$qb->column($this->getColumnName($name), 'TIME');

        return $this;
    }

    public function addDateTime(string $name): self
    {
        if (Connection::getInstance()->getDriver() === DatabaseDriver::PGSQL) {
            return $this->addTimestamp($name);
        } elseif (Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE) {
            return $this->addText($name);
        } else {
            static::$qb->column($this->getColumnName($name), 'DATETIME');
        }

        return $this;
    }

    public function addTimestamp(string $name): self
    {
        static::$qb->column($this->getColumnName($name), 'TIMESTAMP');

        return $this;
    }

    /**
     * Add created_at and updated_at timestamps
     */
    public function addDefaultTimestamps(): self
    {
        static::$qb->timestamps();

        return $this;
    }

    public function addYear(string $name): self
    {
        if (Connection::getInstance()->getDriver() !== DatabaseDriver::MYSQL) {
            return $this->addSmallInt($name);
        }

        static::$qb->column($this->getColumnName($name), 'YEAR');

        return $this;
    }

    public function addBoolean(string $name): self
    {
        if (Connection::getInstance()->getDriver() !== DatabaseDriver::PGSQL) {
            return $this->addTinyInt($name, 1);
        }

        static::$qb->column($this->getColumnName($name), 'BOOLEAN');

        return $this;
    }

    public function constraint(string $name): self
    {
        static::$qb->constraint('fk_'.$name);

        return $this;
    }

    public function addConstraint(string $name): self
    {
        static::$qb->addConstraint('fk_'.$name);

        return $this;
    }

    public function foreignKey(string $column): self
    {
        static::$qb->foreignKey($column);

        return $this;
    }

    public function addConstraintForeignKey(string $name, string $column): self
    {
        return $this->addConstraint($name)->foreignKey($column);
    }

    public function setConstraintForeignKey(string $name, string $column): self
    {
        return $this->constraint($name)->foreignKey($column);
    }

    public function references(string $table, string $column): self
    {
        static::$qb->references($table, $column);

        return $this;
    }

    public function onUpdateCascade(): self
    {
        static::$qb->onUpdateCascade();

        return $this;
    }

    public function onDeleteCascade(): self
    {
        static::$qb->onDeleteCascade();

        return $this;
    }

    public function onUpdateSetNull(): self
    {
        static::$qb->onUpdateSetNull();

        return $this;
    }

    public function onDeleteSetNull(): self
    {
        static::$qb->onDeleteSetNull();

        return $this;
    }

    public function autoIncrement(): self
    {
        static::$qb->autoIncrement();

        return $this;
    }

    public function addPrimaryKey(string $column = 'id', bool $autoIncrement = true): self
    {
        $self = match (Connection::getInstance()->getDriver()) {
            DatabaseDriver::MYSQL => $this->addBigInt($column),
            DatabaseDriver::SQLITE => $this->addInteger($column),
            default => $this->addBigSerial($column)
        };

        static::$qb->primaryKey();

        if ($autoIncrement) {
            $self->autoIncrement();
        }

        return $self;
    }

    public function null(): self
    {
        static::$qb->null();

        return $this;
    }

    public function notNull(): self
    {
        static::$qb->notNull();

        return $this;
    }

    public function unique(): self
    {
        static::$qb->unique();

        return $this;
    }

    public function default(string|int $default): self
    {
        static::$qb->default($default);

        return $this;
    }

    public function run(): bool|PDOStatement
    {
        if (str_contains(static::$qb->toSQL()[0], 'CREATE TABLE')) {
            return static::$qb->migrate();
        }

        if (
            str_contains(static::$qb->toSQL()[0], 'ADD CONSTRAINT') &&
            Connection::getInstance()->getDriver() === DatabaseDriver::SQLITE
        ) {
            return true;
        }

        return static::$qb->flush()->execute();
    }

    protected function getColumnName(string $name): string
    {
        if (static::$alterMode) {
            return " ADD COLUMN $name";
        }

        return $name;
    }
}
