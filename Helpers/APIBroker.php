<?php

namespace DOTW\Helpers;

require_once __DIR__ . '/../Configs/config.php';

class APIBroker {

	private $soap;

	public function __construct() {
		$this->soap = new \SoapClient(null, SOAP_OPTIONS);
	}

	public function doRequest($xml, $action) {
		try {
			$response = $this->soap->__doRequest($xml, API_URL, $action, 1);
			if ($response) {
				$dom = new \SimpleXMLElement(trim($response));
			} else {
				$translation = require_once __DIR__ . '/../Lang/en/api-messages.php';
				echo str_replace(':action', $action, $translation['no_response']);
				exit();
			}
		} catch (SoapFault $sf) {
			$exception = $sf;
			throw $exception;
		} catch (Exception $e) {
			$exception = $e;
			throw $exception;
		}

		if ((isset($this->soap->__soap_fault)) && ($this->soap->__soap_fault != null)) {
			//this is where the exception from __doRequest is stored
			$exception = $this->soap->__soap_fault;

			if ($exception != null) {
				throw $exception;
			}
		}

		return $dom;
	}
}
