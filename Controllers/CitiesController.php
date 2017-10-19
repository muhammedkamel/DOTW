<?php
namespace DOTW\Controllers;

require_once __DIR__ . '/../Models/City.php';
require_once __DIR__ . '/../Helpers/APIBroker.php';
require_once __DIR__ . '/../Helpers/FileReader.php';

use DOTW\Helpers\APIBroker as APIBroker;
use DOTW\Helpers\FileReader as FileReader;
use DOTW\Models\City as City;
use DOTW\Repositories\CityRepository as CityRepository;

class CitiesController {

	private $city;
	private $repo;
	private $broker;

	public function __construct() {
		$this->repo = new CityRepository;
	}

	/**
	 *
	 * Method to add city
	 * @param $code int
	 * @param $name string
	 * @return bool
	 *
	 */
	public function addCountry($code, $name, $county_code) {
		$this->city = new City($code, $name, $country_code);
		return $this->city->save();
	}

	/**
	 *
	 * Method to get all cities by sending xml request to the DOTW
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */
	public function insertCities() {
		$translation = require_once __DIR__ . '/../Lang/en/api-messages.php';
		if ($this->repo->addBulkOfCities($this->requestCities())) {
			echo str_replace(':table', 'Cities', $translation['inserted']);
		} else {
			echo str_replace(':table', 'Cities', $translation['insertion_error']);
		}
	}

	/**
	 *
	 * Method to get all countries by sending xml request to the DOTW
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */
	private function requestCities() {
		$this->broker = new APIBroker;
		$this->fileReader = new FileReader(REQUESTS . '/static-data.xml');
		$requestParams = [
			'%username%' => API_USERNAME,
			'%password%' => API_PASSWORD,
			'%code%' => API_CODE,
			'%source%' => API_SOURCE,
			'%action%' => 'getallcities',
		];
		$request = str_replace(array_keys($requestParams), array_values($requestParams), $this->fileReader->readfile());
		return $this->extractCities($this->broker->doRequest(trim($request), 'getallcities'));
	}

	/**
	 *
	 * Method get country fields from the returned xml response
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */

	private function extractCities($dom): array{
		$cities = &$dom->cities;
		$result = [];
		foreach ($cities->city as $city) {
			$result[] = [
				'code' => $city->code->__toString(),
				'name' => $city->name->__toString(),
				'country_code' => $city->countryCode->__toString(),
			];
		}
		return $result;
	}

}

$cities = new CitiesController;

$cities->insertCities();