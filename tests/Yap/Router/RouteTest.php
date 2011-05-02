<?php
class RouteTest extends PHPUnit_Framework_TestCase
{
    protected $_name = 'test1';
    
    protected $_routeRegexp = 'page/:pageNumber';
    
    protected $_route;
    
    protected $_pageNumber = 12;
    
    public function setUp() 
    {
        parent::setUp();
        $this->_route = new \Yap\Router\Route($this->_name, $this->_routeRegexp, array('pageNumber' => $this->_pageNumber));
    }
    
    public function testType() 
    {
        $this->assertType('\Yap\Router\Route', $this->_route);
    }
    
    public function testCanGetSetName() 
    {
        $this->assertEquals($this->_name, $this->_route->getName());
        $fakeName = 'fakeName';
        $this->_route->setName($fakeName);
        $this->assertEquals($fakeName, $this->_route->getName());
    }
    
    public function testCanGetSetDefaults() 
    {
        $fakeDefaults = array(
            'defaultParam1' => 'fake1',
            'defaultParam2' => 'fake2'
        );
        $this->_route->setDefaults($fakeDefaults);
        $this->assertInternalType('array', $this->_route->getDefaults());
        $this->assertEquals($fakeDefaults, $this->_route->getDefaults());
    }
    
    public function testCanGetIsStatic() 
    {
        $this->assertInternalType('boolean', $this->_route->isStatic());
    }
    
    public function testCanGetSetRoute()
    {
        $fakeRoute = 'route/routePart';
        $this->_route->setRoute($fakeRoute);
        $this->assertEquals($fakeRoute, $this->_route->getRoute());
    }
    
    public function testCanGetStatic()
    {
        $this->assertInternalType('string', $this->_route->getStatic());
    }
    
    public function testMatch()
    {
        //Подаем на вход простой URL
        $result = $this->_route->match('page');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result);
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... и сам pageNumber должен быть равен значению по умолчанию, так как страница передана не была
        $this->assertEquals($this->_pageNumber, $result['pageNumber']);
        
        //Подаем на вход более сложный URL - со страницей
        $result = $this->_route->match('page/24');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result);
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... только вот pageNumber уже должен быть равен переданной странице - 24
        $this->assertEquals(24, $result['pageNumber']);
        
        //Делаем те же тесты, но с завершающими слешами
        $result = $this->_route->match('page/');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result);
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... и сам pageNumber должен быть равен значению по умолчанию, так как страница передана не была
        $this->assertEquals($this->_pageNumber, $result['pageNumber']);
        
        $result = $this->_route->match('page/24/');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result);
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... только вот pageNumber уже должен быть равен переданной странице - 24
        $this->assertEquals(24, $result['pageNumber']);
    }
    
    public function testMisMatch()
    {
        $this->assertFalse($this->_route->match('pages'));
        $this->assertFalse($this->_route->match('pages/24/1'));
    }
}