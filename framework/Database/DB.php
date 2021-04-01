<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

use PDO;
use PDOException;

/**
 * Connection to database
 */
class DB
{
	/**
	 * database class instance
	 * 
	 * @var Database\null
	 */
	protected static $instance = null;

	/**
	 * database connection instance
	 *
	 * @var mixed
	 */
	private $pdo;

	/**
	 * create instance of database connection
	 *
	 * @return void
	 */
	private function __construct()
	{
		try {
            $this->pdo = new PDO('mysql:host=' . config('mysql.host'), config('mysql.username'), config('mysql.password'));
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES ' . config('mysql.charset') . ' COLLATE ' . config('mysql.collation'));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		} catch (PDOException $e) {
			if (config('errors.display')) {
				die($e->getMessage());
			}
		}
    }
        
    /**
     * set database to use
     *
     * @param  string|null $db
     * @return \Framework\Database\DB
     */
    private function setDB(?string $db = null): self
    {
        if (!is_null($db)) {
            $this->pdo->exec('USE ' . $db);
        }

        return $this;
    }

	/**
	 * get connection to database instance
	 *
     * @param  string|null $db 
	 * @return \Framework\Database\DB
	 */
	public static function connection(?string $db = null): self
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
        }
        
        return self::$instance->setDB($db);
    }

	/**
	 * execute sql query
	 *
	 * @return \PDOStatement
	 */
    public function query(string $query): \PDOStatement
    {
        $stmt = null;

        try {
            $stmt = $this->pdo->query($query);
		} catch (PDOException $e) {
			if (config('errors.display')) {
				die($e->getMessage());
			}
        }
        
        return $stmt;
    }

	/**
	 * execute safe sql query
	 *
	 * @return \PDOStatement
	 */
	public function statement(string $query, array $args): \PDOStatement
	{
		$stmt = null;

		try {
			$stmt = $this->pdo->prepare(trim($query));
			$stmt->execute($args);
		} catch (PDOException $e) {
			if (config('errors.display')) {
				die($e->getMessage());
			}
		}

		return $stmt;
	}
}
