<?php

namespace DOTW\Models;

require_once __DIR__ . '/../Repositories/CurrencyRepository.php';

use DOTW\Repositories\CurrencyRepository as CurrencyRepo;

class Country {

	private $repo;
	private $code;
	private $shortcut;
	private $name;

	/**
	 *
	 * Method to get instance of the country repo and to set the name and code
	 * @param $code int
	 * @param $name string
	 *
	 */

	public function __construct(int $code = null, string $shortcut = null, string $name = null) {
		$this->repo = new CurrencyRepo;
		$this->code = $code;
		$this->shortcut = $shortcut;
		$this->name = $name;
	}

	/**
	 *
	 * Method to insert a new country
	 * @return bool
	 *
	 */
	public function save() {
		if ($this->code && $this->shortcut && $this->name) {
			return $this->repo->addCurrency($this->code, $this->shortcut, $this->name);
		}
		return false;
	}

}
