<?php
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';

\Yap\Loader::initAutoloader();

$config = new \Yap\Config\Xml('test.xml');
$container = new \Yap\Router\Container();
$container->addRoutes($config);

var_dump($container->match('/pages/25'));

echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;