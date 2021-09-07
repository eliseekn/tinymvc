<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use Core\Database\Connections\BaseConnection;

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
        $this->db = new BaseConnection(config('database.driver'));
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
        return $this->db->getConnection()->executeStatement($query);
    }

	public function executeQuery(string $query, ?array $args = null)
	{
        return $this->db->getConnection()->executeQuery($query, $args);
	}

    public function schemaExists(string $name)
    {
        return $this->db->getConnection()->schemaExists($name);
    }

    public function tableExists(string $name)
    {
        return $this->db->getConnection()->tableExists($name);
    }

    public function createSchema(string $name)
    {
        $this->db->getConnection()->createSchema($name);
    }

    public function deleteSchema(string $name)
    {
        $this->db->getConnection()->deleteSchema($name);
    }

    public function getPDO()
    {
        return $this->db->getConnection()->getPDO();
    }
}
