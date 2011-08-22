<?php
if (!defined('APPLICATION_ENV')) define('APPLICATION_ENV', 'production');
if (!defined('APPLICATION_PATH')) define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';
\Yap\Loader::initAutoloader();

$config = new Yap\Config\Xml(APPLICATION_PATH . '/configs/t2.xml');

class T extends \Yap\Config\Xml {
    public static $_XML_ELEMENT_ARRAY_KEY_NAME = 'case';
}

$config2 = new T(APPLICATION_PATH . '/configs/t2.xml');

var_dump($config, $config2);


echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;