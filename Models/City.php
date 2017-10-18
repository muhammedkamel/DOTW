<?php

namespace DOTW\Models;

require_once __DIR__ . '/../Repositories/CityRepository.php';

use DOTW\Repositories\CityRepository as CityRepo;

class City {

	private $repo;
	private $code;
	private $name;
	private $country_code;

	/**
	 *
	 * Method to get instance of the City repo and to set the name, code, and country_code
	 * @param $code int
	 * @param $name string
	 * @param $country_code int
	 *
	 */

	public function __construct(int $code = null, string $name = null, int $country_code = null) {
		$this->repo = new CityRepo;
		$this->code = $code;
		$this->name = $name;
		$this->country_code = $country_code;
	}

	/**
	 *
	 * Method to insert a new city
	 * @return bool
	 *
	 */
	public function save() {
		if ($this->code && $this->name && $this->country_code) {
			return $this->repo->addCity($this->code, $this->name, $this->country_code);
		}
		return false;
	}

}
