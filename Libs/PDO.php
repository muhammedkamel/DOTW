<?php
namespace DOTW\Libs;

require_once 'IDB.php';
require_once __DIR__ . '/../Configs/config.php';

use DOTW\Libs\IDB;

class PDO implements IDB {

	private $conn;
	private $stmt;

	/**
	 *
	 * constructor to initialize connection
	 *
	 */
	public function __construct() {
		try {
			$this->conn = new PDO(DRIVER . 'dbname=' . DBNAME . ';host=' . HOST, USERNAME, PASSWORD);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
	public function selectOne(string $query, array $bindings = []): PDO{
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$this->stmt = $stmt;
		return $stmt->fetchObject(__CLASS__);
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
		$this->query = $query;
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$this->stmt = $stmt;
		return $stmt->fetchAll(PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 *
	 * Method to insert
	 * @param $query string
	 * @param $bindings array
	 * @return boolean
	 *
	 */
	public function insert(string $query, array $bindings = []): boolean{
		$this->query = $query;
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$this->stmt = $stmt;
		return $stmt;
	}

	/**
	 *
	 * Method to update
	 * @param $query string
	 * @param $bindings array
	 * @return boolean
	 *
	 */
	public function update(string $query, array $bindings = []): boolean{
		$this->query = $query;
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$this->stmt = $stmt;
		return $stmt;
	}

	/**
	 *
	 * Method to delete
	 * @param $query string
	 * @param $bindings array
	 * @return boolean
	 *
	 */
	public function delete(string $query, array $bindings = []): boolean{
		$this->query = $query;
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$this->stmt = $stmt;
		return $stmt;
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
	 * @return $stmt PDOStatement object
	 *
	 */
	private function bindValues(PDOStatement $stmt, array $bindings): PDOStatement {
		foreach ($bindings as $key => $value) {
			if (is_int($value)) {
				$stmt->bindValues($key, $value, PDO::PARAM_INT);
			} else {
				$stmt->bindValues($key, $value, PDO::PARAM_STR);
			}
		}
		return $stmt;
	}
}