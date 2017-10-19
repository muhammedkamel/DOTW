<?php
namespace DOTW\Controllers;

require_once __DIR__ . '/../Models/Currency.php';
require_once __DIR__ . '/../Helpers/APIBroker.php';
require_once __DIR__ . '/../Helpers/FileReader.php';

use DOTW\Helpers\APIBroker as APIBroker;
use DOTW\Helpers\FileReader as FileReader;
use DOTW\Models\Currency as Currency;
use DOTW\Repositories\CurrencyRepository as CurrencyRepository;

class CurrenciesController {

	private $currency;
	private $repo;
	private $broker;

	public function __construct() {
		$this->repo = new CurrencyRepository;
	}

	/**
	 *
	 * Method to add currency
	 * @param $code int
	 * @param $shortcut string
	 * @param $name string
	 * @return bool
	 *
	 */
	public function addCurrency($code, $shortcut, $name) {
		$this->currency = new Currency($code, $shortcut, $name);
		return $this->currency->save();
	}

	/**
	 *
	 * Method to get all countries by sending xml request to the DOTW
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */
	public function insertCurrencies() {
		$translation = require_once __DIR__ . '/../Lang/en/api-messages.php';
		if ($this->repo->addBulkOfCurrencies($this->requestCurrencies())) {
			echo str_replace(':table', 'Currencies', $translation['inserted']);
		} else {
			echo str_replace(':table', 'Currencies', $translation['insertion_error']);
		}
	}

	/**
	 *
	 * Method to get all countries by sending xml request to the DOTW
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */
	private function requestCurrencies() {
		$this->broker = new APIBroker;
		$this->fileReader = new FileReader(REQUESTS . '/static-data.xml');
		$requestParams = [
			'%username%' => API_USERNAME,
			'%password%' => API_PASSWORD,
			'%code%' => API_CODE,
			'%source%' => API_SOURCE,
			'%action%' => 'getcurrenciesids',
		];
		$request = str_replace(array_keys($requestParams), array_values($requestParams), $this->fileReader->readfile());
		return $this->extractCurrencies($this->broker->doRequest(trim($request), 'getcurrenciesids'));
	}

	/**
	 *
	 * Method get currency fields from the returned xml response
	 * @param $dom SimpleXMLObject
	 * @return $result array
	 *
	 */
	private function extractCurrencies($dom): array{
		$currencies = &$dom->currency->option;
		$result = [];
		foreach ($currencies as $currency) {
			$currency = (array) $currency;
			$result[] = [
				'code' => $currency['@attributes']['value'],
				'shortcut' => $currency['@attributes']['shortcut'],
				'name' => $currency[0],
			];
		}
		return $result;
	}
}

$currenciesController = new CurrenciesController;

$currenciesController->insertCurrencies();