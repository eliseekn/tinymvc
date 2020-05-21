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
    private $query;
    
    /**
     * execute sql query
     *
     * @return void
     */
    private function executeQuery(): void
    {
        $QB = new QueryBuilder();
        $QB->setQuery($this->query);
        $QB->executeQuery();
    }

    /**
     * generate CREATE TABLE query 
     *
     * @param  string $name name of table
     * @return void
     */
    public function table(string $name)
    {
        $this->query = "CREATE TABLE $name (";
        return $this;
    }
    
    /**
     * generate primary key and autoincrement column query
     *
     * @return void
     */
    public function addPrimaryKey(string $name) {
        $this->query .= "$name INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        return $this;
    }

    /**
     * add column of type integer
     *
     * @return void
     */
    public function addInteger(
        string $name, 
        int $size = 11, 
        bool $null = false, 
        bool $unique = false, 
        string $default = ''
    ) {
        $this->query .= "$name INT($size)";
        $this->query .= $null ? ' NULL' : ' NOT NULL';
        $this->query .= $unique ? ' UNIQUE' : '';
        $this->query .= empty($default) ? '' : " DEFAULT '$default'";
        $this->query .= ', ';

        return $this;
    }

    /**
     * add column of type string
     *
     * @return void
     */
    public function addString(
        string $name, 
        int $size = 255, 
        bool $null = false, 
        bool $unique = false, 
        string $default = ''
    ) {
        $this->query .= "$name VARCHAR($size)";
        $this->query .= $null ? ' NULL' : ' NOT NULL';
        $this->query .= $unique ? ' UNIQUE' : '';
        $this->query .= empty($default) ? '' : " DEFAULT '$default'";
        $this->query .= ', ';

        return $this;
    }

    /**
     * add column of type text
     *
     * @return void
     */
    public function addText(
        string $name, 
        bool $null = false, 
        string $default = ''
    ) {
        $this->query .= "$name TEXT";
        $this->query .= $null ? ' NULL' : ' NOT NULL';
        $this->query .= empty($default) ? '' : " DEFAULT '$default'";
        $this->query .= ', ';

        return $this;
    }

    /**
     * add column of type longtext
     *
     * @return void
     */
    public function addLongText(
        string $name, 
        bool $null = false, 
        string $default = ''
    ) {
        $this->query .= "$name LONGTEXT";
        $this->query .= $null ? ' NULL' : ' NOT NULL';
        $this->query .= empty($default) ? '' : " DEFAULT '$default'";
        $this->query .= ', ';

        return $this;
    }

    /**
     * add column of type timestamp
     *
     * @return void
     */
    public function addTimestamp(
        string $name, 
        bool $null = false, 
        string $default = 'CURRENT_TIMESTAMP'
    ) {
        $this->query .= "$name TIMESTAMP";
        $this->query .= $null ? ' NULL' : ' NOT NULL';
        $this->query .= empty($default) ? '' : " DEFAULT '$default'";
        $this->query .= ', ';

        return $this;
    }
    
    /**
     * create new table
     *
     * @return void
     */
    public function create()
    {
        $this->query = rtrim($this->query, ', ');
        $this->query .= ')';
        $this->executeQuery();
    }

    /**
     * drop table if exists
     *
     * @param  string $name table name
     * @return void
     */
    public function dropTable(string $name): void
    {
        $this->query = "DROP TABLE IF EXISTS $name";
        $this->executeQuery();
    }

    /**
     * truncate table if exists
     *
     * @param  string $name table name
     * @return void
     */
    public function truncateTable(string $name): void
    {
        $this->query = "TRUNCATE IF EXISTS $name";
        $this->executeQuery();
    }
}