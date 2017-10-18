<?php

namespace DOTW\Models;

require_once __DIR__ . '/../Repositories/CountryRepository.php';

use DOTW\Repositories\CountryRepository as CountryRepo;

class Country {

	private $repo;
	private $code;
	private $name;

	/**
	 *
	 * Method to get instance of the country repo and to set the name and code
	 * @param $code int
	 * @param $name string
	 *
	 */

	public function __construct(int $code = null, string $name = null) {
		$this->repo = new CountryRepo;
		$this->code = $code;
		$this->name = $name;
	}

	/**
	 *
	 * Method to insert a new country
	 * @return bool
	 *
	 */
	public function save() {
		if ($this->code && $this->name) {
			return $this->repo->addCountry($this->code, $this->name);
		}
		return false;
	}

}
