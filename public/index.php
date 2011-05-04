<?php
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';

\Yap\Loader::initAutoloader();

$parterRoute = new \Yap\Router\Route(array(
                                                    'name' => '4',
                                                    'route' => '*',
                                                    'defaults' => array('categoryId' => 10)
                                                  ));
var_dump($parterRoute->match('/sadads/asdasda/sad'));

echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;