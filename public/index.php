<?php
if (!defined('APPLICATION_ENV')) define('APPLICATION_ENV', 'production');
if (!defined('APPLICATION_PATH')) define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';
\Yap\Loader::initAutoloader();

$em = new \Yap\Event\EventManager();
$em->addEvent('myEvent', function($name = null) {
        return 'this is my event' . $name;
    });

echo $em->myEvent('my adata') . PHP_EOL;


echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;