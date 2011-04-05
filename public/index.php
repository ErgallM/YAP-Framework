<?php
set_include_path(realpath(__DIR__ . '/../library') . PATH_SEPARATOR . get_include_path());

//require_once 'Yap/View.php';
require_once 'Yap/Loader.php';

\Yap\Loader::initAutoloader();
\Yap\Loader::addPath('Yap\View\Helper', 'Yap/View/Helper');

//\Yap\Loader::loadClass('Yap\View');

$view = new \Yap\View(array('helper' => array('Yap\\View\\Helper' => 'Yap/View/Helper/')));
echo $view->render('test.php');
echo $view->HeadTitle('my title');
/*

$view = new \Yap\View(array('file' => 'test.php'));
$view->title = 'title';
echo $view->render();
*/
