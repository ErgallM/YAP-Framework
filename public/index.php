<?php
if (!defined('APPLICATION_ENV')) define('APPLICATION_ENV', 'production');
if (!defined('APPLICATION_PATH')) define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';
\Yap\Loader::initAutoloader();

$application = \Yap\Application::getApplication();

$config = new \Yap\Config\Xml(APPLICATION_PATH . '/configs/application.xml');
var_dump($config->toArray());


echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;