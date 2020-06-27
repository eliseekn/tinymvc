<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

/**
 * Migrations
 * 
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
     * @return mixed
     */
    public static function table(string $name)
    {
        self::$query = "CREATE TABLE " . DB_PREFIX . "$name (";
        return new self();
    }
    
    /**
     * generate primary key and autoincrement column query
     *
     * @param  string $name name of column
     * @return mixed
     */
    public function addPrimaryKey(string $name = 'id') {
        self::$query .= "$name INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        return $this;
    }

    /**
     * add column of type integer
     *
     * @param  string $name name of column
     * @param  int $length value length
     * @param  bool $null null or not
     * @param  bool $unique set column as unique
     * @param  string|null $default default column value
     * @return mixed
     */
    public function addInt(
        string $name, 
        int $length = 11, 
        bool $null = false, 
        bool $unique = false, 
        ?int $default = null
    ) {
        self::$query .= "$name INT($length)";
        self::$query .= $null ? ' NULL' : ' NOT NULL';
        self::$query .= $unique ? ' UNIQUE' : '';
        self::$query .= is_null($default) ? '' : " DEFAULT $default";
        self::$query .= ', ';

        return $this;
    }

    /**
     * add column of type small integer
     *
     * @param  string $name name of column
     * @param  int $length value length
     * @param  bool $null null or not
     * @param  bool $unique set column as unique
     * @param  string|null $default default column value
     * @return mixed
     */
    public function addSmallInt(
        string $name, 
        int $length = 6, 
        bool $null = false, 
        bool $unique = false, 
        ?int $default = null
    ) {
        self::$query .= "$name SMALLINT($length)";
        self::$query .= $null ? ' NULL' : ' NOT NULL';
        self::$query .= $unique ? ' UNIQUE' : '';
        self::$query .= is_null($default) ? '' : " DEFAULT $default";
        self::$query .= ', ';

        return $this;
    }

    /**
     * add column of type string
     *
     * @param  string $name name of column
     * @param  int $length value length
     * @param  bool $null null or not
     * @param  bool $unique set column as unique
     * @param  string $default default column value
     * @return mixed
     */
    public function addString(
        string $name, 
        int $length = 255, 
        bool $null = false, 
        bool $unique = false, 
        ?string $default = null
    ) {
        self::$query .= "$name VARCHAR($length)";
        self::$query .= $null ? ' NULL' : ' NOT NULL';
        self::$query .= $unique ? ' UNIQUE' : '';
        self::$query .= is_null($default) ? '' : " DEFAULT '$default'";
        self::$query .= ', ';

        return $this;
    }

    /**
     * add column of type text
     *
     * @param  string $name name of column
     * @param  bool $null null or not
     * @return mixed
     */
    public function addText(string $name, bool $null = false) {
        self::$query .= "$name TEXT";
        self::$query .= $null ? ' NULL' : ' NOT NULL';
        self::$query .= ', ';

        return $this;
    }

    /**
     * add column of type longtext
     *
     * @param  string $name name of column
     * @param  bool $null null or not
     * @return mixed
     */
    public function addLongText(string $name, bool $null = false) {
        self::$query .= "$name LONGTEXT";
        self::$query .= $null ? ' NULL' : ' NOT NULL';
        self::$query .= ', ';

        return $this;
    }

    /**
     * add column of type timestamp
     *
     * @param  string $name name of column
     * @param  bool $null null or not
     * @param  string $default default column value
     * @return mixed
     */
    public function addTimestamp(
        string $name, 
        bool $null = false, 
        string $default = 'CURRENT_TIMESTAMP'
    ) {
        self::$query .= "$name TIMESTAMP";
        self::$query .= $null ? ' NULL' : ' NOT NULL';
        self::$query .= empty($default) ? '' : " DEFAULT $default";
        self::$query .= ', ';

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
        self::$query = "DROP TABLE IF EXISTS " . DB_PREFIX . "$name";
        self::executeQuery();
    }
}
