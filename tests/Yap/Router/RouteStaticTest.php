<?php
class RouteStaticTest extends PHPUnit_Framework_TestCase
{
    protected $_name = 'test2';

    protected $_routeRegexp = 'pages/about';

    protected $_route;

    protected $_module = 'index';

    public function setUp()
    {
        parent::setUp();
        $this->_route = new \Yap\Router\RouteStatic(new \Yap\Config(array(
                                                   'name' => $this->_name,
                                                   'route' => $this->_routeRegexp,
                                                   'defaults' => array('module' => $this->_module)
                                              )));
    }

    public function testType()
    {
        $this->assertInstanceOf('\Yap\Router\RouteStatic', $this->_route);
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

    public function testCanGetSetRoute()
    {
        $fakeRoute = 'route/routePart';
        $this->_route->setRoute($fakeRoute);
        $this->assertEquals($fakeRoute, $this->_route->getRoute());
    }

    public function testMatch()
    {
        //Подаем на вход простой URL
        $result = $this->_route->match('pages/about');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result);
        //... и этот массив должен содержать module
        $this->assertArrayHasKey('module', $result);
        //... и сам module должен быть равен значению по умолчанию, так как страница передана не была
        $this->assertEquals($this->_module, $result['module']);

        //Делаем те же тесты, но с завершающими слешами
        $route = $this->_route;
        $result = $route('pages/about/');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result);
        //... и этот массив должен содержать module
        $this->assertArrayHasKey('module', $result);
        //... и сам module должен быть равен значению по умолчанию, так как страница передана не была
        $this->assertEquals($this->_module, $result['module']);
    }

    public function testMisMatch()
    {
        $this->assertFalse($this->_route->match('module'));
        $this->assertFalse($this->_route->match('pages/about/222'));
    }
}