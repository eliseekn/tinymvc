<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use PDOStatement;

/**
 * Manage migrations tables
 */
class Migration
{
    /**
	 * @var \Core\Database\QueryBuilder
	 */
    protected static QueryBuilder $qb;

    public static function driver(): string
    {
        return config('app.env') === 'test'
            ? config('tests.database.driver')
            : config('database.driver');
    }
    
    public static function createTable(string $name): self
    {
        static::$qb = QueryBuilder::createTable($name);
        return new self();
    }

    public static function addColumn(string $table): self
    {
        static::$qb = QueryBuilder::addColumn($table);
        return new self();
    }

    public static function renameColumn(string $table, string $old, string $new): self
    {
        static::$qb = QueryBuilder::renameColumn($table, $old, $new);
        return new self();
    }

    /**
     * Generate CHANGE COLUMN query 
     */
    public static function updateColumn(string $table, string $column): self
    {
        static::$qb = QueryBuilder::updateColumn($table, $column);
        return new self();
    }

    public static function deleteColumn(string $table, string $column): self
    {
        static::$qb = QueryBuilder::deleteColumn($table, $column);
        return new self();
    }

    public static function dropTable(string $table): false|PDOStatement
    {
        return QueryBuilder::dropTable($table)->execute();
    }

    public static function dropForeign(string $table, string $name): false|PDOStatement
    {
        return QueryBuilder::dropForeign($table, 'fk_' . $name)->execute();
    }

    public static function disableForeignKeyCheck(): false|PDOStatement
    {
        $query = static::driver() === 'mysql' ? 'SET foreign_key_checks = 0' : 'PRAGMA foreign_keys = OFF';
        return QueryBuilder::setQuery($query)->execute();
    }

    public static function enableForeignKeyCheck(): false|PDOStatement
    {
        $query = static::driver() === 'mysql' ? 'SET foreign_key_checks = 1' : 'PRAGMA foreign_keys = ON';
        return QueryBuilder::setQuery($query)->execute();
    }

    public function addReal(string $name): self 
    {
        self::$qb->column($name, "REAL");
        return $this;
    }

    public function addInteger(string $name): self 
    {
        self::$qb->column($name, "INTEGER");
        return $this;
    }

    public function addInt(string $name, int $size = 11, bool $unsigned = false): self 
    {
        self::$qb->column($name, "INT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    public function addTinyInt(string $name, int $size = 4, bool $unsigned = false): self 
    {
        self::$qb->column($name, "TINYINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    public function addSmallInt(string $name, int $size = 6, bool $unsigned = false): self 
    {
        self::$qb->column($name, "SMALLINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    public function addMediumInt(string $name, int $size = 8, bool $unsigned = false): self 
    {
        self::$qb->column($name, "MEDIUMINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    public function addBigInt(string $name, int $size = 20, bool $unsigned = false): self 
    {
        self::$qb->column($name, "BIGINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    public function addFloat(string $name, int $size = 10, int $precision = 2): self 
    {
        self::$qb->column($name, "FLOAT($size, $precision)");
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

	public function addForeignKey(string $column, string $name): self
	{
		self::$qb->foreignKey('fk_' . $name, $column);
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
    
    public function primaryKey(): self
    {
        self::$qb->primaryKey();
        return $this;
    }
    
    public function addPrimaryKey(string $column, bool $auto_increment = true): self
    {
        $pk = self::driver() === 'mysql' ? $this->addBigInt($column) : $this->addInteger($column);
        $pk->primaryKey();

        if ($auto_increment) {
            $pk->autoIncrement();
        }

        return $pk;
    }
    
    public function addCurrentTimestamp(string $created_at = 'created_at', string $updated_at = 'updated_at'): self
    {
        self::$qb->addCurrentTimestamp($created_at, $updated_at);
        return $this;
    }
    
    public function nullable(): self
    {
        self::$qb->nullable();
        return $this;
    }

    public function unique(): self
    {
        self::$qb->unique();
        return $this;
    }

    public function default($default): self
    {
        self::$qb->default($default);
        return $this;
    }
    
    public function migrate(bool $table = true): false|PDOStatement
    {
        return $table ? self::$qb->migrate() : self::$qb->flush()->execute();
    }
}
