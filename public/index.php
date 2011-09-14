<?php
if (!defined('APPLICATION_ENV')) define('APPLICATION_ENV', 'production');
if (!defined('APPLICATION_PATH')) define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';
\Yap\Loader::initAutoloader();

$ev = new \Yap\Event\Event('test');

echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;