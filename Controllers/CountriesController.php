<?php
namespace DOTW\Controllers;

require_once __DIR__ . '/../Models/Country.php';
require_once __DIR__ . '/../Helpers/APIBroker.php';
require_once __DIR__ . '/../Helpers/FileReader.php';

use DOTW\Helpers\APIBroker as APIBroker;
use DOTW\Helpers\FileReader as FileReader;
use DOTW\Models\Country as Country;
use DOTW\Repositories\CountryRepository as CountryRepository;

class CountriesController {

	private $country;
	private $repo;
	private $broker;
	private $fileReader;

	public function __construct() {
		$this->repo = new CountryRepository;
	}

	/**
	 *
	 * Method to  add country
	 * @param $code int
	 * @param $name string
	 * @return bool
	 *
	 */
	public function addCountry($code, $name) {
		$this->country = new Country($code, $name);
		return $this->country->save();
	}

	/**
	 *
	 * Method to get country with country code
	 * @param $code int
	 * @return object
	 *
	 */
	public function getCountry($code) {
		return $this->repo->getCountryByCode($code);
	}

	/**
	 *
	 * Method to get all countries by sending xml request to the DOTW
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */
	public function insertContries() {
		$translation = require_once __DIR__ . '/../Lang/en/api-messages.php';
		if ($this->repo->addBulkOfCountries($this->requestCountries())) {
			echo str_replace(':table', 'Countries', $translation['inserted']);
		} else {
			echo str_replace(':table', 'Countries', $translation['insertion_error']);
		}
	}

	/**
	 *
	 * Method to get all countries by sending xml request to the DOTW
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */
	private function requestCountries() {
		$this->broker = new APIBroker;
		$this->fileReader = new FileReader(REQUESTS . '/static-data.xml');
		$requestParams = [
			'%username%' => API_USERNAME,
			'%password%' => API_PASSWORD,
			'%code%' => API_CODE,
			'%source%' => API_SOURCE,
			'%action%' => 'getallcountries',
		];
		$request = str_replace(array_keys($requestParams), array_values($requestParams), $this->fileReader->readfile());
		return $this->extractCountries($this->broker->doRequest(trim($request), 'getallcountries'));
	}

	/**
	 *
	 * Method get country fields from the returned xml response
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */

	private function extractCountries($dom): array{
		$countries = &$dom->countries;
		$result = [];
		foreach ($countries->country as $country) {
			$result[] = ['code' => $country->code->__toString(), 'name' => $country->name->__toString()];
		}
		return $result;
	}
}

// $countriesController = new CountriesController;

// $countriesController->insertContries();