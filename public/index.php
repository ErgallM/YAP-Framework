<?php
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

$r = new Yap\Router\Route\Module('default', 'pages/:pageNumber');

$r2 = new Yap\Router\Route\Module('test', 'pages');