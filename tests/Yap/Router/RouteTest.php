<?php
class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $route = new \Yap\Router\Route('test1', 'page/:pageNumber');
        $this->assertFalse($route->match('pages'));
        $this->assertFalse($route->match('pages/24/1'));
    }
}