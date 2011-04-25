<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '/home/ergallm/www/YAP-Framework');

require_once 'PHPUnit/Framework.php';
require_once 'library/Yap/Router/Route.php';

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $route = new \Yap\Router\Route('test1', 'page/:pageNumber');
        $this->assertFalse('pages');
        $this->assertFalse('pages/24/1');
    }
}