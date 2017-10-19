<?php

namespace DOTW\Repositories;

require_once __DIR__ . '/ICurrencyRepository.php';
require_once __DIR__ . '/../Libs/PDOConnection.php';

use DOTW\Libs\PDOConnection as PDO;
use DOTW\Repositories\ICurrencyRepository as ICurrencyRepository;

class CurrencyRepository implements ICurrencyRepository {

	private $db;

	/**
	 *
	 * Method to initialize connection
	 *
	 */
	public function __construct() {
		$this->db = new PDO;
	}

	/**
	 *
	 * Method to insert new currency
	 * @param $code int
	 * @param $shortcut string
	 * @param $name string
	 * @return bool
	 *
	 */
	public function addCurrency(int $code, string $shortcut, string $name): bool {
		return $this->db->insert('INSERT INTO currencies SET code= ?, shortcut= ?, name= ?', [$code, $shortcut, $name]);
	}

	/**
	 *
	 * Method to insert a new collection of currencies
	 * @param $currencies array
	 * @return bool
	 *
	 */
	public function addBulkOfCurrencies(array $currencies): bool{
		$bindings = $this->getBindings($currencies);
		$values = $this->bindCurrencies($currencies);
		return $this->db->insert("INSERT INTO currencies (code, shortcut, name) VALUES $values", $bindings);
	}

	/**
	 *
	 * Method to format the currencies values
	 * @param $currencies array
	 * @return string
	 *
	 */
	private function bindCurrencies(array $currencies): string{
		$values = '';
		foreach ($currencies as $currency) {
			$values .= '(';
			foreach ($currency as $field) {
				$values .= '?,';
			}
			$values = rtrim($values, ',');
			$values .= '),';
		}
		return rtrim($values, ',');
	}

	/**
	 *
	 * Method to get the bindings array
	 * @param $currencies array
	 * @return array
	 *
	 */
	private function getBindings(array $currencies): array{
		$result = [];
		foreach ($currencies as $currency) {
			foreach ($currency as $field) {
				$result[] = $field;
			}
		}
		return $result;
	}

	/**
	 *
	 * Method to get currency by id
	 * @param $id int
	 * @return object
	 *
	 */
	public function getCurrenciesByID(int $id) {
		return $this->db->selectOne("SELECT * FROM currencies WHERE id = ? LIMIT 1", [$id]);
	}

	/**
	 *
	 * Method to get currency by code
	 * @param $code int
	 * @return object
	 *
	 */
	public function getCurrencyByCode(int $code) {
		return $this->db->selectOne("SELECT * FROM currencies WHERE code = ? LIMIT 1", [$code]);
	}

	/**
	 *
	 * Method to get currency by id
	 * @param $shortcut string
	 * @return array
	 *
	 */
	public function getCurrenciesByShortcut(string $shortcut): array{
		return $this->db->select("SELECT * FROM cities WHERE shortcut LIKE ?", ["%$shortcut%"]);
	}

	/**
	 *
	 * Method to get currency by name
	 * @param $name string
	 * @return array
	 *
	 */
	public function getCurrenciesByName(string $name): array{
		return $this->db->select("SELECT * FROM cities WHERE name LIKE ?", ["%$name%"]);
	}

	/**
	 *
	 * Method to get all currencies
	 * @return array
	 *
	 */
	public function getAllCurrencies(): array{
		return $this->db->select("SELECT * FROM cities");
	}

}
