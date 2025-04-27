<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database;

use Core\Enums\DatabaseDriver;
use PDOStatement;

/**
 * Manage schemes
 */
class Schema
{
    protected static QueryBuilder $qb;

    public static function createTable(string $name): self
    {
        static::$qb = QueryBuilder::createTable($name);

        return new self;
    }

    public static function alterTable(string $table): self
    {
        static::$qb = QueryBuilder::alter($table);

        return new self;
    }

    public function addColumn(): self
    {
        static::$qb->addColumn();

        return $this;
    }

    public function renameColumn(string $old, string $new): self
    {
        static::$qb->renameColumn($old, $new);

        return $this;
    }

    /**
     * Generate CHANGE COLUMN query.
     */
    public function updateColumn(string $column): self
    {
        static::$qb->updateColumn($column);

        return $this;
    }

    public function deleteColumn(string $column): self
    {
        static::$qb->deleteColumn($column);

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
        $query = match (QueryBuilder::connection()->getDriver()) {
            DatabaseDriver::MYSQL => 'SET foreign_key_checks = 0',
            DatabaseDriver::PGSQL => 'SET session_replication_role = replica',
            default => 'PRAGMA foreign_keys = OFF',
        };

        return QueryBuilder::setQuery($query)->execute();
    }

    public static function enableForeignKeyCheck(): false|PDOStatement
    {
        $query = match (QueryBuilder::connection()->getDriver()) {
            DatabaseDriver::MYSQL => 'SET foreign_key_checks = 1',
            DatabaseDriver::PGSQL => 'SET session_replication_role = origin',
            default => 'PRAGMA foreign_keys = ON',
        };

        return QueryBuilder::setQuery($query)->execute();
    }

    public function addReal(string $name): self
    {
        self::$qb->column($name, 'REAL');

        return $this;
    }

    public function addInteger(string $name): self
    {
        self::$qb->column($name, 'INTEGER');

        return $this;
    }

    public function addInt(string $name, int $size = 11, bool $unsigned = false): self
    {
        if (QueryBuilder::connection()->getDriver() === DatabaseDriver::PGSQL) {
            self::$qb->column($name, 'INT');

            return $this;
        }

        self::$qb->column($name, "INT($size)".($unsigned ? ' UNSIGNED' : ''));

        return $this;
    }

    public function addTinyInt(string $name, int $size = 4, bool $unsigned = false): self
    {
        if (QueryBuilder::connection()->getDriver() === DatabaseDriver::PGSQL) {
            self::$qb->column($name, 'SMALLINT');

            return $this;
        }

        self::$qb->column($name, "TINYINT($size)".($unsigned ? ' UNSIGNED' : ''));

        return $this;
    }

    public function addSmallInt(string $name, int $size = 6, bool $unsigned = false): self
    {
        if (QueryBuilder::connection()->getDriver() === DatabaseDriver::PGSQL) {
            self::$qb->column($name, 'SMALLINT');

            return $this;
        }

        self::$qb->column($name, "SMALLINT($size)".($unsigned ? ' UNSIGNED' : ''));

        return $this;
    }

    public function addMediumInt(string $name, int $size = 8, bool $unsigned = false): self
    {
        if (QueryBuilder::connection()->getDriver() === DatabaseDriver::PGSQL) {
            self::$qb->column($name, 'INT');

            return $this;
        }

        self::$qb->column($name, "MEDIUMINT($size)".($unsigned ? ' UNSIGNED' : ''));

        return $this;
    }

    public function addBigInt(string $name, int $size = 20, bool $unsigned = false): self
    {
        if (QueryBuilder::connection()->getDriver() === DatabaseDriver::PGSQL) {
            self::$qb->column($name, 'BIGINT');

            return $this;
        }

        self::$qb->column($name, "BIGINT($size)".($unsigned ? ' UNSIGNED' : ''));

        return $this;
    }

    public function addSerial(string $name): self
    {
        self::$qb->column($name, 'SERIAL');

        return $this;
    }

    public function addBigSerial(string $name): self
    {
        self::$qb->column($name, 'BIGSERIAL');

        return $this;
    }

    public function addFloat(string $name, int $size = 10, int $precision = 2): self
    {
        if (QueryBuilder::connection()->getDriver() === DatabaseDriver::PGSQL) {
            self::$qb->column($name, "NUMERIC($size, $precision)");
        } elseif (QueryBuilder::connection()->getDriver() === DatabaseDriver::SQLITE) {
            self::$qb->column($name, 'REAL');
        } else {
            self::$qb->column($name, "FLOAT($size, $precision)");
        }

        return $this;
    }

    public function addDouble(string $name, int $size = 10, int $precision = 2): self
    {
        self::$qb->column($name, "DOUBLE($size, $precision)");

        return $this;
    }

    public function addDecimal(string $name, int $size = 10, int $precision = 2): self
    {
        self::$qb->column($name, "DECIMAL($size, $precision)");

        return $this;
    }

    public function addChar(string $name): self
    {
        self::$qb->column($name, 'CHAR(1)');

        return $this;
    }

    public function addString(string $name, int $size = 255): self
    {
        self::$qb->column($name, "VARCHAR($size)");

        return $this;
    }

    public function addText(string $name): self
    {
        self::$qb->column($name, 'TEXT');

        return $this;
    }

    public function addTinyText(string $name): self
    {
        self::$qb->column($name, 'TINYTEXT');

        return $this;
    }

    public function addMediumText(string $name): self
    {
        self::$qb->column($name, 'MEDIUMTEXT');

        return $this;
    }

    public function addLongText(string $name): self
    {
        self::$qb->column($name, 'LONGTEXT');

        return $this;
    }

    public function addBlob(string $name): self
    {
        self::$qb->column($name, 'BLOB');

        return $this;
    }

    public function addTinyBlob(string $name): self
    {
        self::$qb->column($name, 'TINYBLOB');

        return $this;
    }

    public function addMediumBlob(string $name): self
    {
        self::$qb->column($name, 'MEDIUMBLOB');

        return $this;
    }

    public function addLongBlob(string $name): self
    {
        self::$qb->column($name, 'LONGBLOB');

        return $this;
    }

    public function addDate(string $name): self
    {
        self::$qb->column($name, 'DATE');

        return $this;
    }

    public function addTime(string $name): self
    {
        self::$qb->column($name, 'TIME');

        return $this;
    }

    public function addDateTime(string $name): self
    {
        if (QueryBuilder::connection()->getDriver() === DatabaseDriver::PGSQL) {
            return $this->addTimestamp($name);
        }

        self::$qb->column($name, 'DATETIME');

        return $this;
    }

    public function addTimestamp(string $name): self
    {
        self::$qb->column($name, 'TIMESTAMP');

        return $this;
    }

    public function addYear(string $name): self
    {
        self::$qb->column($name, 'YEAR');

        return $this;
    }

    public function addBoolean(string $name): self
    {
        $this->addTinyInt($name, 1);

        return $this;
    }

    public function constraint(string $name): self
    {
        self::$qb->constraint('fk_'.$name);

        return $this;
    }

    public function addConstraint(string $name): self
    {
        self::$qb->addConstraint('fk_'.$name);

        return $this;
    }

    public function foreignKey(string $column): self
    {
        self::$qb->foreignKey($column);

        return $this;
    }

    public function addConstraintForeignKey(string $name, string $column): self
    {
        self::$qb->addConstraint($name)->foreignKey($column);

        return $this;
    }

    public function constraintForeignKey(string $name, string $column): self
    {
        self::$qb->constraint($name)->foreignKey($column);

        return $this;
    }

    public function references(string $table, string $column): self
    {
        self::$qb->references($table, $column);

        return $this;
    }

    public function onUpdateCascade(): self
    {
        self::$qb->onUpdateCascade();

        return $this;
    }

    public function onDeleteCascade(): self
    {
        self::$qb->onDeleteCascade();

        return $this;
    }

    public function onUpdateSetNull(): self
    {
        self::$qb->onUpdateSetNull();

        return $this;
    }

    public function onDeleteSetNull(): self
    {
        self::$qb->onDeleteSetNull();

        return $this;
    }

    public function autoIncrement(): self
    {
        self::$qb->autoIncrement();

        return $this;
    }

    public function addPrimaryKey(string $column = 'id', bool $autoIncrement = true): self
    {
        $self = match (QueryBuilder::connection()->getDriver()) {
            DatabaseDriver::MYSQL => $this->addBigInt($column),
            DatabaseDriver::SQLITE => $this->addInteger($column),
            default => $this->addBigSerial($column)
        };

        self::$qb->primaryKey();

        if ($autoIncrement) {
            $self->autoIncrement();
        }

        return $self;
    }

    public function null(): self
    {
        self::$qb->null();

        return $this;
    }

    public function notNull(): self
    {
        self::$qb->notNull();

        return $this;
    }

    public function unique(): self
    {
        self::$qb->unique();

        return $this;
    }

    public function default(string|int $default): self
    {
        self::$qb->default($default);

        return $this;
    }

    public function run(): bool|PDOStatement
    {
        if (str_contains(self::$qb->toSQL()[0], 'CREATE TABLE')) {
            return self::$qb->timestamps()->migrate();
        }

        if (
            str_contains(self::$qb->toSQL()[0], 'ADD CONSTRAINT') &&
            QueryBuilder::connection()->getDriver() === DatabaseDriver::SQLITE
        ) {
            return true;

        }

        return self::$qb->flush()->execute();
    }
}
