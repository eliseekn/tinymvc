<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database\Connection;

use PDO;
use PDOException;

class MySQLConnection implements ConnectionInterface
{
	/**
	 * @var PDO
	 */
	protected $pdo;

    /**
     * @throws PDOException
	 */
	public function __construct()
	{
		try {
            $this->pdo = new PDO('mysql:host=' . config('database.mysql.host') . ';port=' . config('database.mysql.port'), config('database.mysql.username'), config('database.mysql.password'));
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES ' . config('database.mysql.charset') . ' COLLATE ' . config('database.mysql.collation'));
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		} catch (PDOException $e) {
            throw new PDOException($e->getMessage());
		}
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * @throws PDOException
	 */
    public function executeStatement(string $query)
    {
        try {
            return $this->pdo->exec($query);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
        }
    }

	/**
     * @throws PDOException
	 */
	public function executeQuery(string $query, ?array $args = null)
	{
		$stmt = null;

		try {
			$stmt = $this->pdo->prepare(trim($query));
			$stmt->execute($args);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
		}

		return $stmt;
	}

    public function schemaExists(string $name)
    {
        $stmt = $this->executeQuery('
            SELECT schema_name FROM information_schema.schemata WHERE schema_name = "' . $name .'"
        ');

        return !($stmt->fetch() === false);
    }

    public function tableExists(string $name)
    {
        $stmt = $this->executeQuery('
            SELECT * FROM information_schema.tables WHERE table_schema = "' . config('database.name') .'" 
            AND table_name = "' . $name . '" LIMIT 1
        ');

        return !($stmt->fetch() === false);
    }

    public function createSchema(string $name)
    {
        $this->executeStatement('
            CREATE DATABASE ' . $name . ' CHARACTER SET ' . config('database.mysql.charset') . 
            ' COLLATE ' . config('database.mysql.collation')
        );
    }

    public function deleteSchema(string $name)
    {
        $this->executeStatement("DROP DATABASE IF EXISTS $name");
    }
}
