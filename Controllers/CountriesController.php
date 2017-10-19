<?php
namespace DOTW\Controllers;

require_once __DIR__ . '/../Models/Country.php';
require_once __DIR__ . '/../Helpers/APIBrocker.php';

use DOTW\Helpers\APIBrocker as APIBrocker;
use DOTW\Models\Country as Country;
use DOTW\Repositories\CountryRepository as CountryRepository;

class CountriesController {

	private $country;
	private $repo;
	private $brocker;

	public function __construct() {
		$this->repo = new CountryRepository;
	}

	/**
	 *
	 * Method to get add country
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
	 * Method to get all countries by sending xml request to the DOTW
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
		$this->brocker = new APIBrocker;
		ob_start();
		?>
		<?xml version="1.0" encoding="UTF-8"?>
		<customer>
			<username><?=API_USERNAME?></username>
			<password><?=API_PASSWORD?></password>
			<id><?=API_CODE?></id>
			<source><?=API_SOURCE?></source>
			<request command="getallcountries"></request>
		</customer>
		<?php
$request = ob_get_clean();
		return $this->extractCountries($this->brocker->doRequest($request, 'getallcountries'));
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