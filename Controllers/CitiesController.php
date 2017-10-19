<?php
namespace DOTW\Controllers;

require_once __DIR__ . '/../Models/City.php';
require_once __DIR__ . '/../Helpers/APIBrocker.php';

use DOTW\Helpers\APIBrocker as APIBrocker;
use DOTW\Models\City as City;
use DOTW\Repositories\CityRepository as CityRepository;

class CitiesController {

	private $city;
	private $repo;
	private $brocker;

	public function __construct() {
		$this->repo = new CityRepository;
	}

	/**
	 *
	 * Method to get add city
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
	 * Method to get all countries by sending xml request to the DOTW
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
		$this->brocker = new APIBrocker;
		ob_start();
		?>
		<?xml version="1.0" encoding="UTF-8"?>
		<customer>
			<username><?=API_USERNAME?></username>
			<password><?=API_PASSWORD?></password>
			<id><?=API_CODE?></id>
			<source><?=API_SOURCE?></source>
			<request command="getallcities">
				<return>
					<fields>
						<field>countryCode</field>
					</fields>
				</return>
			</request>
		</customer>
		<?php
$request = ob_get_clean();
		return $this->extractCities($this->brocker->doRequest($request, 'getallcities'));
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

// $cities = new CitiesController;

// $cities->insertCities();