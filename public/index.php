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

$r = new Yap\Router\Route\Module('default', 'pages/:pageNumber/*', null, array('pageNumber' => '\d+'));
var_dump($r->match('pages/25')); echo '<hr />';
var_dump($r->match('pages/25/23')); echo '<hr />';

$r = new Yap\Router\Route\Module('default', 'shop/:categoryId/articles/:articleName', null, array('categoryId' => '\d+'));
var_dump($r->match('shop/25')); echo '<hr />';
var_dump($r->match('shop/25/articlesssss')); echo '<hr />';
var_dump($r->match('shop/25/articles/Test')); echo '<hr />';
var_dump($r->match('shop/25/articles/test/10')); echo '<hr />';

$r = new Yap\Router\Route\Module('default', 'shop/category', array('module' => 'default', 'controller' => 'index'));
var_dump($r->match('shop/category')); echo '<hr />';
