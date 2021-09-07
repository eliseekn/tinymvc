<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database\Connections;

class BaseConnection
{
    protected $connection;

    public function __construct(string $driver)
	{
        $this->connection = $driver === 'mysql' ? new MySQLConnection() : new SQLiteConnection();
    }

    public function getConnection()
    {
        return $this->connection;
    }
}