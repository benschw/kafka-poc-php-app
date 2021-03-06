<?php

use Demo\DemoApplication;
use Demo\DemoConfiguration;

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors" , 1);

require_once __DIR__ . '/../vendor/autoload.php';

try {
	$svc = new DemoApplication(new DemoConfiguration());
	$svc->run();
} catch (\Exception $e) {
	error_log($e);
	http_response_code(500);
}

