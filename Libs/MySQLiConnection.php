<?php
namespace DOTW\Libs;

require_once 'IDB.php';
require_once __DIR__ . '/../Configs/config.php';

use DOTW\Libs\IDB;

class MySQLiConnection implements IDB {

	private $conn;
	private $stmt;

	/**
	 *
	 * constructor to initialize connection
	 *
	 */
	public function __construct() {
		try {
			$this->conn = new \mysqli(HOST, USERNAME, PASSWORD, DBNAME);
		} catch (Exception $e) {
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
	public function selectOne(string $query, array $bindings = []): MySQLiConnection{
		$stmt = $this->conn->prepare($query);
		$stmt = $this->bindValues($stmt, $bindings);
		$stmt->execute();
		$result = $stmt->get_result();
		$this->stmt = $stmt;
		return $result->fetch_object(__CLASS__);
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
		$result = $stmt->get_result();
		$this->stmt = $stmt;
		$results = [];
		while ($row = $result->fetch_object(__CLASS__)) {
			$results[] = $row;
		}
		return $results;
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
		return $this->stmt->affected_rows;
	}

	/**
	 *
	 * Method to bind all values with there types
	 * @param $stmt PDOStatement object
	 * @param $bindings array
	 *
	 */
	private function bindValues($stmt, array $bindings) {
		if ($bindings) {
			$params = [];
			$types = '';
			foreach ($bindings as $value) {
				if (is_int($value)) {
					$types .= 'i';
				} else {
					$types .= 's';
				}
			}
			$params[] = &$types;
			for ($i = 0; $i < count($bindings); $i++) {
				$params[] = &$bindings[$i];
			}
			call_user_func_array(array($stmt, 'bind_param'), $params);
		}
		return $stmt;
	}

	public function __destruct() {
		$this->conn->close();
	}
}