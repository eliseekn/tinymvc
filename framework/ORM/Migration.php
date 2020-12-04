<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\ORM;

/**
 * Manage database migrations
 */
class Migration
{
    /**
	 * @var \Framework\ORM\Builder
	 */
    protected static $query;
    
    /**
     * generate CREATE TABLE query 
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public static function table(string $name): self
    {
        self::$query = Builder::table($name);
        return new self();
    }

    /**
     * add column of type integer
     *
     * @param  string $name
     * @param  int $length
     * @return \Framework\ORM\Migration
     */
    public function addInt(string $name, int $length = 11): self 
    {
        self::$query->column($name, "INT($length)");
        return $this;
    }

    /**
     * add column of type float
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addFloat(string $name): self 
    {
        self::$query->column($name, "FLOAT");
        return $this;
    }

    /**
     * add column of type double
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addDouble(string $name): self 
    {
        self::$query->column($name, "DOUBLE");
        return $this;
    }

    /**
     * add column of type small integer
     *
     * @param  string $name
     * @param  int $length
     * @return \Framework\ORM\Migration
     */
    public function addSmallInt(string $name, int $length = 6): self 
    {
        self::$query->column($name, "SMALLINT($length)");
        return $this;
    }

    /**
     * add column of type big integer
     *
     * @param  string $name
     * @param  int $length
     * @return \Framework\ORM\Migration
     */
    public function addBigInt(string $name, int $length = 20): self 
    {
        self::$query->column($name, "BIGINT($length)");
        return $this;
    }

    /**
     * add column of type char
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addChar(string $name): self 
    {
        self::$query->column($name, 'CHAR(1)');
        return $this;
    }

    /**
     * add column of type string
     *
     * @param  string $name
     * @param  int $length
     * @return \Framework\ORM\Migration
     */
    public function addString(string $name, int $length = 255): self 
    {
        self::$query->column($name, "VARCHAR($length)");
        return $this;
    }

    /**
     * add column of type text
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addText(string $name): self 
    {
        self::$query->column($name, 'TEXT');
        return $this;
    }

    /**
     * add column of type longtext
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addLongText(string $name): self 
    {
        self::$query->column($name, 'LONGTEXT');
        return $this;
    }

    /**
     * add column of type date
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addDate(string $name): self 
    {
        self::$query->column($name, 'DATE');
        return $this;
    }

    /**
     * add column of type time
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addTime(string $name): self 
    {
        self::$query->column($name, 'TIME');
        return $this;
    }
    
    /**
     * add column of type datetime
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addDateTime(string $name): self 
    {
        self::$query->column($name, 'DATETIME');
        return $this;
    }

    /**
     * add column of type timestamp
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addTimestamp(string $name): self 
    {
        self::$query->column($name, 'TIMESTAMP');
        return $this;
    }

    /**
     * add column of type boolean
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addBoolean(string $name): self 
    {
        self::$query->column($name, 'TINYINT(1)');
        return $this;
    }
    
    /**
     * add foreign key constraints
     *
	 * @param  string $name
	 * @param  string $column
     * @return \Framework\ORM\Migration
     */
	public function addForeignKey(string $name, string $column): self
	{
		self::$query->foreign($name, $column);
        return $this;
	}
	
	/**
	 * add references
	 *
	 * @param  string $table
	 * @param  string $column
	 * @return \Framework\ORM\Migration
	 */
	public function references(string $table, string $column): self
	{
		self::$query->references($table, $column);
        return $this;
	}
	
	/**
	 * add on update attribute
	 *
	 * @return \Framework\ORM\Migration
	 */
	public function onUpdate(): self
	{
		self::$query->onUpdate();
        return $this;
	}
	
	/**
	 * add on delete attribute
	 *
	 * @return \Framework\ORM\Migration
	 */
	public function onDelete(): self
	{
		self::$query->onDelete();
        return $this;
	}
	
	/**
	 * add cascade attribute
	 *
	 * @return \Framework\ORM\Migration
	 */
	public function cascade(): self
	{
		self::$query->cascade();
        return $this;
	}
	
	/**
	 * add set null attribute
	 *
	 * @return \Framework\ORM\Migration
	 */
	public function setNull(): self
	{
		self::$query->setNull();
        return $this;
	}
    
    /**
     * add primary key and auto increment attributes
     *
     * @return \Framework\ORM\Migration
     */
    public function primaryKey(): self
    {
        self::$query->primaryKey();
        return $this;
    }
    
    /**
     * add null attribute
     * 
     * @return \Framework\ORM\Migration
     */
    public function null(): self
    {
        self::$query->null();
        return $this;
    }

    /**
     * add unique attribute
     *
     * @return \Framework\ORM\Migration
     */
    public function unique(): self
    {
        self::$query->unique();
        return $this;
    }

    /**
     * add default attribute
     *
     * @param  mixed $default
     * @return \Framework\ORM\Migration
     */
    public function default($default) : self
    {
        self::$query->default($default);
        return $this;
    }
    
    /**
     * create new table
     *
     * @return void
     */
    public function create(): void
    {
        self::$query->create()->execute();
    }

    /**
     * drop table if exists
     *
     * @param  string $table
     * @return void
     */
    public static function drop(string $table): void
    {
        Builder::drop($table)->execute();
    }
}
