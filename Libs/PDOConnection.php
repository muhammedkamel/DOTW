<?php
namespace DOTW\Libs;

require_once 'IDB.php';
require_once __DIR__ . '/../Configs/config.php';

use DOTW\Libs\IDB;

class PDOConnection implements IDB {

	private $conn;
	private $stmt;

	/**
	 *
	 * constructor to initialize connection
	 *
	 */
	public function __construct() {
		try {
			$this->conn = new \PDO(DRIVER . ':dbname=' . DBNAME . ';host=' . HOST, USERNAME, PASSWORD);
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo $e->getMessage();
			die();
		}
	}

	/**
	 *
	 * Method to select one row
	 * @param $query string
	 * @param $bindings array
	 * @return $result PDO object the columns values of the row or false
	 *
	 */
	public function selectOne(string $query, array $bindings = []): PDOConnection{
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$this->stmt = $stmt;
		return $this->stmt->fetchObject(__CLASS__);
	}

	/**
	 *
	 * Method to select Collection of rows
	 * @param $query string
	 * @param $bindings array
	 * @return $result array of PDO objects or false
	 *
	 */
	public function select(string $query, array $bindings = []): array{
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$this->stmt = $stmt;
		return $this->stmt->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 *
	 * Method to insert
	 * @param $query string
	 * @param $bindings array
	 * @return bool
	 *
	 */
	public function insert(string $query, array $bindings = []): bool{
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$result = $stmt->execute();
		$this->stmt = $stmt;
		return $result;
	}

	/**
	 *
	 * Method to update
	 * @param $query string
	 * @param $bindings array
	 * @return bool
	 *
	 */
	public function update(string $query, array $bindings = []): bool{
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$result = $stmt->execute();
		$this->stmt = $stmt;
		return $result;
	}

	/**
	 *
	 * Method to delete
	 * @param $query string
	 * @param $bindings array
	 * @return bool
	 *
	 */
	public function delete(string $query, array $bindings = []): bool{
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$result = $stmt->execute();
		$this->stmt = $stmt;
		return $result;
	}

	/**
	 *
	 * Method to get the affected rows of the last query
	 * @return int
	 *
	 */
	public function affectedRows(): int {
		return $this->stmt->rowCount();
	}

	/**
	 *
	 * Method to bind all values with there types
	 * @param $stmt PDOStatement object
	 * @param $bindings array
	 *
	 */
	private function bindValues(\PDOStatement $stmt, array $bindings): \PDOStatement {
		for ($i = 0; $i < count($bindings); $i++) {
			$param = $i + 1;
			if (is_int($bindings[$i])) {
				$stmt->bindParam($param, $bindings[$i], \PDO::PARAM_INT);
			} else {
				$stmt->bindParam($param, $bindings[$i], \PDO::PARAM_STR);
			}
		}
		return $stmt;
	}

	public function __destruct() {
		$this->conn = null;
	}
}