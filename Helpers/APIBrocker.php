<?php

namespace DOTW\Helpers;

require_once __DIR__ . '/../Configs/config.php';

class APIBrocker {

	private $soap;

	public function __construct() {
		$this->soap = new \SoapClient(null, SOAP_OPTIONS);
	}

	public function doRequest($xml, $action) {
		try {
			$dom = new \SimpleXMLElement($this->soap->__doRequest($xml, API_URL, $action, 1));
		} catch (SoapFault $sf) {
			$exception = $sf;
		} catch (Exception $e) {
			$exception = $e;
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
