<?php
define('APPLICATION_ENV', 'debug');
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';

\Yap\Loader::initAutoloader();



echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;