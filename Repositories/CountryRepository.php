<?php

namespace DOTW\Repositories;

require_once __DIR__ . '/ICountryRepository.php';
require_once __DIR__ . '/../Libs/PDOConnection.php';

use DOTW\Libs\PDOConnection as PDO;
use DOTW\Repositories\ICountryRepository;

class CountryRepository implements ICountryRepository {

	private $db;

	/**
	 *
	 * Initialize db connection
	 *
	 */

	public function __construct() {
		$this->db = new PDO;
	}

	/**
	 *
	 * Method to insert country
	 * @param $code int
	 * @param $name string
	 * @return bool
	 */

	public function addCountry(int $code, string $name): bool {
		return $this->db->insert('INSERT INTO countries SET code= ?, name= ?', [$code, $name]);
	}

	/**
	 *
	 * Method to add collection of countries
	 * @param $countries
	 * @return bool
	 *
	 */

	public function addBulkOfCountries(array $countries): bool{
		$bindings = $this->getBindings($countries);
		$values = $this->bindCountries($countries);
		return $this->db->insert("INSERT INTO countries (code, name) VALUES $values", $bindings);
	}

	/**
	 *
	 * Method to format the values
	 * @param $countries array
	 * @return string
	 *
	 */

	private function bindCountries(array $countries): string{
		$values = '';
		foreach ($countries as $country) {
			$values .= '(';
			foreach ($country as $field) {
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
	 * @param $countries array
	 * @return array
	 *
	 */

	private function getBindings(array $countries): array{
		$result = [];
		foreach ($countries as $country) {
			foreach ($country as $field) {
				$result[] = $field;
			}
		}
		return $result;
	}

	/**
	 *
	 * Method to get country by id
	 * @param $id int
	 * @return object
	 *
	 */
	public function getCountryByID(int $id) {
		return $this->db->selectOne("SELECT * FROM countries WHERE id = ? LIMIT 1", [$id]);
	}

	/**
	 *
	 * Method to get country by code
	 * @param $code int
	 * @return object
	 *
	 */
	public function getCountryByCode(int $code) {
		return $this->db->selectOne("SELECT * FROM countries WHERE code = ? LIMIT 1", [$code]);
	}

	/**
	 *
	 * Method to get country by name
	 * @param $name string
	 * @return array
	 *
	 */
	public function getCountryByName(string $name): array{
		return $this->db->select("SELECT * FROM countries WHERE name LIKE ?", ["%$name%"]);
	}

	/**
	 *
	 * Method to get all countries
	 * @return object
	 *
	 */
	public function getAllCountries(): array{
		return $this->db->select("SELECT * FROM countries");
	}
}
