<?php
class RouteTest extends PHPUnit_Framework_TestCase
{
    protected $_name = 'test1';
    
    protected $_routeRegexp = 'page/:pageNumber';
    
    protected $_route;
    
    public function setUp() 
    {
        parent::setUp();
        $this->_route = new \Yap\Router\Route($this->_name, $this->_routeRegexp);
    }
    
    public function testType() 
    {
        $this->assertType('\Yap\Router\Route', $this->_route);
    }
    
    public function testCanGetName() 
    {
        $this->assertEquals($this->_name, $this->_route->getName());
    }
    
    public function testMatch()
    {
        $this->assertInternalType('array', $this->_route->match('page'));
        $this->assertInternalType('array', $this->_route->match('page/24'));
        
        //Test trimming ability
        $this->assertInternalType('array', $this->_route->match('page/'));
        $this->assertInternalType('array', $this->_route->match('page/24/'));
    }
    
    public function testMisMatch()
    {
        $this->assertFalse($this->_route->match('pages'));
        $this->assertFalse($this->_route->match('pages/24/1'));
    }
}