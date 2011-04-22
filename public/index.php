<?php
$time_start = microtime(true); 

set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

//require_once 'Yap/View.php';
require_once 'Yap/Loader.php';

\Yap\Loader::initAutoloader();
\Yap\Loader::addPath('Yap\View\Helper', 'Yap/View/Helper');

/*
$view = new \Yap\View(array('helper' => array('Yap\\View\\Helper' => 'Yap/View/Helper/')));
echo $view->render('test.php');
echo $view->HeadTitle('my title');
*/

$r = new \Yap\Router\Route\Module('test', 'shop/:categoryId/:articleId/*', null, array('categoryId' => '\d+', 'articleId' => '\d+'));
echo var_export($r->match('shop'), true) . '-false' . PHP_EOL;
echo var_export($r->match('sss'), true) . '-false' . PHP_EOL;
echo var_export($r->match('shop/25'), true) . '-false' . PHP_EOL;
echo var_export($r->match('shop/25/22/sd'), true) . '-true' . PHP_EOL;

$r = new \Yap\Router\Route\Module('test2', 'shop', array('shopId' => 1));
echo var_export($r->match('shop/'), true) . '-true' . PHP_EOL;
echo var_export($r->match('sss'), true) . '-false' . PHP_EOL;
echo var_export($r->match('shop/25'), true) . '-false' . PHP_EOL;

echo 'time: ' . round(microtime(true) - $time_start, 10);