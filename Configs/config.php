<?php

define('USERNAME', 'hamada');
define('PASSWORD', 'Hamada@93');
define('HOST', 'localhost');
define('DBNAME', 'dotw');
define('DRIVER', 'mysql');

define('API_USERNAME', 'cool16');
define('API_PASSWORD', '596d59dcd8726d6559806b377a828e62');
define('API_CODE', '1319485');
define('API_SOURCE', 1);
define('API_URL', 'http://xmldev.DOTWconnect.com/gatewayV3.dotw');
define('SOAP_OPTIONS',
	[
		'location' => API_URL,
		'uri' => '/gatewayV3.dotw',
		'style' => SOAP_DOCUMENT,
		'use' => SOAP_LITERAL,
		'cache_wsdl' => WSDL_CACHE_NONE,
		'exceptions' => FALSE,
		'trace' => TRUE,
	]
);