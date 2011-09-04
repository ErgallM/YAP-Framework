<?php
if (!defined('APPLICATION_ENV')) define('APPLICATION_ENV', 'production');
if (!defined('APPLICATION_PATH')) define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

require_once 'Yap/Loader.php';
\Yap\Loader::initAutoloader();

$em = \Yap\Event\EventManager::getInstance();
$em->addEvent('test', function() {echo 'test' . PHP_EOL;});

$em->test();

echo PHP_EOL;

$em->test->append('test2', function() {echo 'test2' . PHP_EOL;});
$em->test();

echo PHP_EOL;

$event = $em->getEvent('test2');
if (null !== $event) {
    $event->prepend('test3', function() {echo 'test3' . PHP_EOL;});
    $em->test();
}

echo 'time: ' . round(microtime(true) - $time_start, 10) . PHP_EOL;