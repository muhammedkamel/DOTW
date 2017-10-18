<?php

namespace DOTW\Repositories;

require_once __DIR__ . '/ICityRepository.php';
require_once __DIR__ . '/../Libs/PDOConnection.php';

use DOTW\Libs\PDOConnection as PDO;
use DOTW\Repositories\ICityRepository;

class CityRepository implements ICityRepository {

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
	 * Method to insert new city
	 * @param $id int
	 * @param $name string
	 * @param $country_code int
	 * @return bool
	 *
	 */
	public function addCity(int $code, string $name, int $country_code): bool {
		return $this->db->insert('INSERT INTO cities SET code= ?, name= ?, country_code= ?', [$code, $name, $country_code]);
	}

	/**
	 *
	 * Method insert a collection of cities
	 * @param $cities array
	 * @return bool
	 *
	 */
	public function addBulkOfCities(array $cities): bool{
		$bindings = $this->getBindings($cities);
		$values = $this->bindCities($cities);
		return $this->db->insert("INSERT INTO cities (code, name, country_code) VALUES $values", $bindings);
	}

	/**
	 *
	 * Method to format the cities values
	 * @param $cities string
	 * @return string
	 *
	 */
	private function bindCities($cities): string{
		$values = '';
		foreach ($cities as $city) {
			$values .= '(' . preg_replace('/\w+/', '?', implode(',', $city)) . '),';
		}
		return rtrim($values, ',');
	}

	/**
	 *
	 * Method to get the bindings array
	 * @param $array array
	 * @return array
	 *
	 */
	private function getBindings($cities): array{
		$result = [];
		foreach ($cities as $city) {
			foreach ($city as $field) {
				$result[] = $field;
			}
		}
		return $result;
	}

	/**
	 *
	 * Method to get city by id
	 * @param $id int
	 * @return object
	 *
	 */
	public function getCityByID(int $id) {
		return $this->db->selectOne("SELECT * FROM cities WHERE id = ? LIMIT 1", [$id]);
	}

	/**
	 *
	 * Method to get city by code
	 * @param $code int
	 * @return object
	 *
	 */
	public function getCityByCode(int $code) {
		return $this->db->selectOne("SELECT * FROM cities WHERE code = ? LIMIT 1", [$code]);
	}

	/**
	 *
	 * Method to get city name
	 * @param $name string
	 * @return array
	 *
	 */
	public function getCitiesByName(string $name): array{
		return $this->db->select("SELECT * FROM cities WHERE name LIKE ?", ["%$name%"]);
	}

	/**
	 *
	 * Method to get cities by country code
	 * @param $country_code int
	 * @return array
	 *
	 */
	public function getCitiesByCountryCode(int $country_code): array{
		return $this->db->select("SELECT * FROM cities WHERE country_code = ?", [$country_code]);
	}

	/**
	 *
	 * Method to get all cities
	 * @return array
	 *
	 */
	public function getAllCities(): array{
		return $this->db->select("SELECT * FROM cities");
	}
}
