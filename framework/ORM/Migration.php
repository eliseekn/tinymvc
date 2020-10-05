<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

/**
 * Manage database migrations
 */
class Migration
{
    /**
	 * sql query string
	 *
	 * @var string
	 */
    protected static $query = '';
    
    /**
     * execute sql query
     *
     * @return void
     */
    private static function executeQuery(): void
    {
        Query::DB()->setQuery(self::$query);
        Query::DB()->executeQuery();
    }

    /**
     * generate CREATE TABLE query 
     *
     * @param  string $name name of table
     * @return \Framework\ORM\Migration
     */
    public static function table(string $name): self
    {
        self::$query = "CREATE TABLE " . config('database.table_prefix') . "$name (";
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
        self::$query .= "$name INT($length) NOT NULL, ";
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
        self::$query .= "$name SMALLINT($length) NOT NULL, ";
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
        self::$query .= "$name BIGINT($length) NOT NULL, ";
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
        self::$query .= "$name CHAR(1) NOT NULL, ";
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
        self::$query .= "$name VARCHAR($length) NOT NULL, ";
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
        self::$query .= "$name TEXT NOT NULL, ";
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
        self::$query .= "$name LONGTEXT NOT NULL, ";
        return $this;
    }

    /**
     * add column of type timestamp
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addDate(string $name): self 
    {
        self::$query .= "$name TIMESTAMP NOT NULL, ";
        return $this;
    }

    /**
     * add column of type timestamp with default current timestamp sql value
     *
     * @param  string $name
     * @return \Framework\ORM\Migration
     */
    public function addTimestamp(string $name): self 
    {
        self::$query .= "$name TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
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
        self::$query .= "$name TINYINT(1) NOT NULL, ";
        return $this;
    }
    
    /**
     * add primary key and auto increment attributes
     *
     * @return \Framework\ORM\Migration
     */
    public function primaryKey(): self
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= ' AUTO_INCREMENT PRIMARY KEY, ';
        return $this;
    }
    
    /**
     * add null attribute
     * 
     * @return mixed
     */
    public function null(): self
    {
        self::$query = str_replace('NOT NULL, ', 'NULL, ', self::$query);
        return $this;
    }

    /**
     * add unique attribute
     *
     * @return mixed
     */
    public function unique(): self
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= ' UNIQUE, ';
        return $this;
    }

    /**
     * add default attribute
     *
     * @param  mixed $default
     * @return mixed
     */
    public function default($default) : self
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= " DEFAULT '$default', ";
        return $this;
    }
    
    /**
     * create new table
     *
     * @return void
     */
    public function create(): void
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= ')';
        self::executeQuery();
    }

    /**
     * drop table if exists
     *
     * @param  string $name name of table
     * @return void
     */
    public static function dropTable(string $name): void
    {
        self::$query = "DROP TABLE IF EXISTS " . config('database.table_prefix') . "$name";
        self::executeQuery();
    }
}
