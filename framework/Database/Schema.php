<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

/**
 * Manage database schemas
 */
class Schema
{
    /**
	 * @var \Framework\Database\Builder $builder
	 */
    protected static $builder;
    
    /**
     * generate CREATE TABLE query 
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public static function createTable(string $name): self
    {
        self::$builder = Builder::table($name);
        return new self();
    }

    /**
     * generate ADD COLUMN query 
     *
     * @param  string $table
     * @return \Framework\Database\Migration
     */
    public static function addColumn(string $table): self
    {
        self::$builder = Builder::addColumn($table);
        return new self();
    }

    /**
     * generate RENAME COLUMN query 
     *
     * @param  string $table
     * @param  string $old
     * @param  string $new
     * @return \Framework\Database\Migration
     */
    public static function renameColumn(string $table, string $old, string $new): self
    {
        self::$builder = Builder::renameColumn($table, $old, $new);
        return new self();
    }

    /**
     * generate CHANGE query 
     *
     * @param  string $table
     * @param  string $column
     * @return \Framework\Database\Migration
     */
    public static function updateColumn(string $table, string $column): self
    {
        self::$builder = Builder::updateColumn($table, $column);
        return new self();
    }

    /**
     * generate CREATE COLUMN query 
     *
     * @param  string $table
     * @param  string $column
     * @return \Framework\Database\Migration
     */
    public static function deleteColumn(string $table, string $column): self
    {
        self::$builder = Builder::deleteColumn($table, $column);
        return new self();
    }

    /**
     * add column of type integer
     *
     * @param  string $name
     * @param  int $size
     * @param  bool $unsigned
     * @return \Framework\Database\Migration
     */
    public function addInt(string $name, int $size = 11, bool $unsigned = false): self 
    {
        self::$builder->column($name, "INT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    /**
     * add column of type tinyint
     *
     * @param  string $name
     * @param  int $size
     * @param  bool $unsigned
     * @return \Framework\Database\Migration
     */
    public function addTinyInt(string $name, int $size = 4, bool $unsigned = false): self 
    {
        self::$builder->column($name, "TINYINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    /**
     * add column of type smallint
     *
     * @param  string $name
     * @param  int $size
     * @param  bool $unsigned
     * @return \Framework\Database\Migration
     */
    public function addSmallInt(string $name, int $size = 6, bool $unsigned = false): self 
    {
        self::$builder->column($name, "SMALLINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    /**
     * add column of type mediumint
     *
     * @param  string $name
     * @param  int $size
     * @param  bool $unsigned
     * @return \Framework\Database\Migration
     */
    public function addMediumInt(string $name, int $size = 8, bool $unsigned = false): self 
    {
        self::$builder->column($name, "MEDIUMINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    /**
     * add column of type big integer
     *
     * @param  string $name
     * @param  int $size
     * @param  bool $unsigned
     * @return \Framework\Database\Migration
     */
    public function addBigInt(string $name, int $size = 20, bool $unsigned = false): self 
    {
        self::$builder->column($name, "BIGINT($size)" . ($unsigned ? ' UNSIGNED' : ''));
        return $this;
    }

    /**
     * add column of type float
     *
     * @param  string $name
     * @param  int $size
     * @param  int $precision
     * @return \Framework\Database\Migration
     */
    public function addFloat(string $name, int $size = 10, int $precision = 2): self 
    {
        self::$builder->column($name, "FLOAT($size, $precision)");
        return $this;
    }

    /**
     * add column of type double
     *
     * @param  string $name
     * @param  int $size
     * @param  int $precision
     * @return \Framework\Database\Migration
     */
    public function addDouble(string $name, int $size = 10, int $precision = 2): self 
    {
        self::$builder->column($name, "DOUBLE($size, $precision)");
        return $this;
    }

    /**
     * add column of type decimal
     *
     * @param  string $name
     * @param  int $size
     * @param  int $precision
     * @return \Framework\Database\Migration
     */
    public function addDecimal(string $name, int $size = 10, int $precision = 2): self 
    {
        self::$builder->column($name, "DECIMAL($size, $precision)");
        return $this;
    }

    /**
     * add column of type char
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addChar(string $name): self 
    {
        self::$builder->column($name, 'CHAR(1)');
        return $this;
    }

    /**
     * add column of type string
     *
     * @param  string $name
     * @param  int $size
     * @return \Framework\Database\Migration
     */
    public function addString(string $name, int $size = 255): self 
    {
        self::$builder->column($name, "VARCHAR($size)");
        return $this;
    }

    /**
     * add column of type text
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addText(string $name): self 
    {
        self::$builder->column($name, 'TEXT');
        return $this;
    }

    /**
     * add column of type tinytext
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addTinyText(string $name): self 
    {
        self::$builder->column($name, 'TINYTEXT');
        return $this;
    }

    /**
     * add column of type mediumtext
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addMediumText(string $name): self 
    {
        self::$builder->column($name, 'MEDIUMTEXT');
        return $this;
    }

    /**
     * add column of type longtext
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addLongText(string $name): self 
    {
        self::$builder->column($name, 'LONGTEXT');
        return $this;
    }

    /**
     * add column of type blob
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addBlob(string $name): self 
    {
        self::$builder->column($name, 'BLOB');
        return $this;
    }

    /**
     * add column of type tinyblob
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addTinyBlob(string $name): self 
    {
        self::$builder->column($name, 'TINYBLOB');
        return $this;
    }

    /**
     * add column of type mediumblob
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addMediumBlob(string $name): self 
    {
        self::$builder->column($name, 'MEDIUMBLOB');
        return $this;
    }

    /**
     * add column of type longblob
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addLongBlob(string $name): self 
    {
        self::$builder->column($name, 'LONGBLOB');
        return $this;
    }

    /**
     * add column of type enum
     *
     * @param  string $name
     * @param  array $values
     * @return \Framework\Database\Migration
     */
    public function addEnum(string $name, array $values): self 
    {
        self::$builder->column($name, 'ENUM(' . implode(',', $values) . ')');
        return $this;
    }

    /**
     * add column of type date
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addDate(string $name): self 
    {
        self::$builder->column($name, 'DATE');
        return $this;
    }

    /**
     * add column of type time
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addTime(string $name): self 
    {
        self::$builder->column($name, 'TIME');
        return $this;
    }
    
    /**
     * add column of type datetime
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addDateTime(string $name): self 
    {
        self::$builder->column($name, 'DATETIME');
        return $this;
    }

    /**
     * add column of type timestamp
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addTimestamp(string $name): self 
    {
        self::$builder->column($name, 'TIMESTAMP');
        return $this;
    }

    /**
     * add column of type year
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addYear(string $name): self 
    {
        self::$builder->column($name, 'YEAR');
        return $this;
    }

    /**
     * add column of type boolean
     *
     * @param  string $name
     * @return \Framework\Database\Migration
     */
    public function addBoolean(string $name): self 
    {
        $this->addTinyInt($name, 1);
        return $this;
    }
    
    /**
     * add foreign key constraints
     *
	 * @param  string $column
	 * @param  string|null $name
     * @return \Framework\Database\Migration
     */
	public function addForeignKey(string $column, ?string $name = null): self
	{
        if (is_null($name)) {
            $name = 'fk_' . $column;
        }

		self::$builder->foreignKey($name, $column);
        return $this;
	}
	
	/**
	 * add references
	 *
	 * @param  string $table
	 * @param  string $column
	 * @return \Framework\Database\Migration
	 */
	public function references(string $table, string $column): self
	{
		self::$builder->references($table, $column);
        return $this;
	}
	
	/**
	 * add on update attribute
	 *
	 * @return \Framework\Database\Migration
	 */
	public function onUpdate(): self
	{
		self::$builder->onUpdate();
        return $this;
	}
	
	/**
	 * add on delete attribute
	 *
	 * @return \Framework\Database\Migration
	 */
	public function onDelete(): self
	{
		self::$builder->onDelete();
        return $this;
	}
	
	/**
	 * add cascade attribute
	 *
	 * @return \Framework\Database\Migration
	 */
	public function cascade(): self
	{
		self::$builder->cascade();
        return $this;
	}
	
	/**
	 * add set null attribute
	 *
	 * @return \Framework\Database\Migration
	 */
	public function setNull(): self
	{
		self::$builder->setNull();
        return $this;
	}
    
    /**
     * add primary key and auto increment attributes
     *
     * @return \Framework\Database\Migration
     */
    public function primaryKey(): self
    {
        self::$builder->primaryKey();
        return $this;
    }
    
    /**
     * add null attribute
     * 
     * @return \Framework\Database\Migration
     */
    public function null(): self
    {
        self::$builder->null();
        return $this;
    }

    /**
     * add unique attribute
     *
     * @return \Framework\Database\Migration
     */
    public function unique(): self
    {
        self::$builder->unique();
        return $this;
    }

    /**
     * add default attribute
     *
     * @param  mixed $default
     * @return \Framework\Database\Migration
     */
    public function default($default) : self
    {
        self::$builder->default($default);
        return $this;
    }
    
    /**
     * create new table
     *
     * @return void
     */
    public function create()
    {
        self::$builder->create()->execute();
    }
    
    /**
     * execute query
     *
     * @return void
     */
    public function execute()
    {
        self::$builder->flush();
        self::$builder->execute();
    }

    /**
     * drop table if exists
     *
     * @param  string $table
     * @return void
     */
    public static function dropTable(string $table): void
    {
        Builder::drop($table)->execute();
    }
}
