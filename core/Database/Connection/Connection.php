<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database\Connection;

/**
 * Manage database connection
 */
class Connection
{
	/**
	 * @var \Core\Database\Database
	 */
	protected static $instance = null;

    protected $db;

	private function __construct()
	{
        $driver = config('app.env') === 'test' ? $driver = config('testing.database.driver') : config('database.driver');

        $this->db = $driver === 'mysql' ? new MySQLConnection() : new SQLiteConnection();
    }

	public static function getInstance(): self
	{
		if (is_null(self::$instance)) {
			self::$instance = new static();
        }
        
        return self::$instance;
    }

    public function executeStatement(string $query)
    {
        return $this->db->executeStatement($query);
    }

	public function executeQuery(string $query, ?array $args = null)
	{
        return $this->db->executeQuery($query, $args);
	}

    public function schemaExists(string $name)
    {
        return $this->db->schemaExists($name);
    }

    public function tableExists(string $name)
    {
        return $this->db->tableExists($name);
    }

    public function createSchema(string $name)
    {
        $this->db->createSchema($name);
    }

    public function deleteSchema(string $name)
    {
        $this->db->deleteSchema($name);
    }

    public function getPDO()
    {
        return $this->db->getPDO();
    }
}
