<?php
class RouteContainerTest extends PHPUnit_Framework_TestCase
{
    protected $_route1;
    protected $_route2;
    protected $_route3;

    protected $_container;

    public function setUp()
    {
        parent::setUp();

        $this->_route1 = new \Yap\Router\Route(array(
                                                    'name' => 'route1',
                                                    'route' => '*'
                                               ));

        $this->_route2 = new \Yap\Router\Route(array(
                                                    'name' => 'route2',
                                                    'route' => '/pages/:pageNumber',
                                                    'reqs' => array('pageNumber' => '\d+'),
                                                    'defaults' => array('pageNumber' => 1)
                                               ));

        $this->_route3 = new \Yap\Router\RouteStatic(new \Yap\Config(array(
                                                                          'name' => 'route3',
                                                                          'route' => '/pages/about.html',
                                                                          'defaults' => array('module' => 'index',
                                                                                              'action' => 'about')
                                                                     )));

        $this->_container = new \Yap\Router\Container();
        $this->_container->addRoute($this->_route1)
            ->addRoute($this->_route2)
            ->addRoute($this->_route3);
    }

    public function testCanAddGetRemoveRoute()
    {
        $fakeRoute = new \Yap\Router\RouteStatic(array('name' => 'test', 'route' => 'test'));
        $this->assertInstanceOf('\Yap\Router\Container', $this->_container->addRoute($fakeRoute));
        $this->assertInstanceOf('\Yap\Router\RouteInterface', $this->_container->getRoute('test'));
        $this->assertInstanceOf('\Yap\Router\Container', $this->_container->removeRoute('test'));
        $this->assertNull($this->_container->getRoute('test'));
    }

    public function testMatch()
    {
        $result = $this->_container->match('/pages/about.html');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('routeName', $result);
        $this->assertEquals('route3', $result['routeName']);


        $result = $this->_container->match('/asdjoaspdkoas/asdjoajsod as/das das ');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('routeName', $result);
        $this->assertEquals('route1', $result['routeName']);

        $result = $this->_container->match('pages/25');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('routeName', $result);
        $this->assertArrayHasKey('pageNumber', $result);
        $this->assertEquals('route2', $result['routeName']);
        $this->assertEquals(25, $result['pageNumber']);

        $this->_container->removeRoute('route1');
        $result = $this->_container->match('asdasda/asdassda');
        $this->assertFalse($result);
    }
}
