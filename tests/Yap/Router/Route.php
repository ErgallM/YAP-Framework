<?php
require_once 'PHPUnit/Framework.php';
require_once 'library/Yap/Router/RouteStatic.php';

use \Yap\Router;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $route = new Yap\Router\Route('test1', 'page/:pageNumber');
        $this->assertFalse('pages');
        $this->assertFalse('pages/24/1');
    }
}