<?php
class RouteTest extends PHPUnit_Framework_TestCase
{
    protected $_name = 'test';
    
    protected $_routeName = 'test1';
    protected $_routeRegexp = 'page/:pageNumber';
    protected $_route;
    protected $_pageNumber = 12;

    protected $_routeStarRegexp = 'shop/:categoryId/:articleId/*';
    protected $_routeStar;
    protected $_categoryId = 5;
    protected $_articleId = 10;
    protected $_order = 'name';

    protected $_routeStaticRegexp = 'shop';
    protected $_routeStatic;

    protected $_parterRoute;
    protected $_parterRouteRegexp = 'shop/:categoryId/articles/:articleId';

    protected $_routeEndStat;
    protected $_routeEndStatRegexp = ':module/controller/index';

    protected $_routeDefault;

    
    public function setUp() 
    {
        parent::setUp();
        $this->_route = new \Yap\Router\Route(new \Yap\Config\Config(array(
                                                   'name' => $this->_routeName,
                                                   'route' => $this->_routeRegexp,
                                                   'defaults' => array('pageNumber' => $this->_pageNumber)
                                              )));

        $this->_routeStar = new \Yap\Router\Route(new \Yap\Config\Config(array(
                                                    'name' => $this->_name . '2',
                                                    'route' => $this->_routeStarRegexp,
                                                    'reqs' => array('categoryId' => '\d+', 'articleId' => '\d+')
                                                  )));

        $this->_routeStatic = new \Yap\Router\Route(array(
                                                    'name' => $this->_name . '3',
                                                    'route' => $this->_routeStaticRegexp,
                                                    'defaults' => array('categoryId' => $this->_categoryId)
                                                  ));

        $this->_parterRoute = new \Yap\Router\Route(array(
                                                    'name' => $this->_name . 4,
                                                    'route' => $this->_parterRouteRegexp,
                                                    'defaults' => array('categoryId' => $this->_categoryId),
                                                    'reqs' => array('categoryId' => '\d+', 'articleId' => '\d+')
                                                  ));

        $this->_routeEndStat = new \Yap\Router\Route(array(
                                                        'name' => $this->_name . 5,
                                                        'route' => $this->_routeEndStatRegexp,
                                                     ));

        $this->_routeDefault = new \Yap\Router\Route(array(
                                                          'name' => 'default',
                                                          'route' => 'default/*'
                                                     ));
    }
    
    public function testType() 
    {
        $this->assertInstanceOf('\Yap\Router\Route', $this->_route);
    }
    
    public function testCanGetSetName() 
    {
        $this->assertEquals($this->_routeName, $this->_route->getName());
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

    public function testCanSetGetReqs()
    {
        $result = $this->_parterRoute->getReqs();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('categoryId', $result);
        $this->assertArrayHasKey('articleId', $result);
        $this->assertEquals('\d+', $result['categoryId']);
        $this->assertEquals('\d+', $result['articleId']);

        $fakeReqs = array('categoryId' => '*');
        $this->_parterRoute->setReqs($fakeReqs);
        $this->assertEquals($fakeReqs, $this->_parterRoute->getReqs());

    }
    
    public function testMatch()
    {
        //Подаем на вход простой URL
        $result = $this->_route->match('page');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result, 'test1');
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... и сам pageNumber должен быть равен значению по умолчанию, так как страница передана не была
        $this->assertEquals($this->_pageNumber, $result['pageNumber']);
        
        //Подаем на вход более сложный URL - со страницей
        $result = $this->_route->match('page/24');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result, 'test2');
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... только вот pageNumber уже должен быть равен переданной странице - 24
        $this->assertEquals(24, $result['pageNumber']);
        
        //Делаем те же тесты, но с завершающими слешами
        $result = $this->_route->match('page/');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result, 'test3');
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... и сам pageNumber должен быть равен значению по умолчанию, так как страница передана не была
        $this->assertEquals($this->_pageNumber, $result['pageNumber']);
        
        $result = $this->_route->match('page/24/');
        //Результат должен быть массивом...
        $this->assertInternalType('array', $result, 'test4');
        //... и этот массив должен содержать pageNumber
        $this->assertArrayHasKey('pageNumber', $result);
        //... только вот pageNumber уже должен быть равен переданной странице - 24
        $this->assertEquals(24, $result['pageNumber']);

        //Подаем на вход простой url
        $result = $this->_routeStar->match('shop/1/11');
        $this->assertInternalType('array', $result, 'test5');
        $this->assertArrayHasKey('categoryId', $result);
        $this->assertArrayHasKey('articleId', $result);
        $this->assertEquals(1, $result['categoryId']);
        $this->assertEquals(11, $result['articleId']);


        //Подаем на вход простой url
        $result = $this->_routeStar->match('shop/1/11');
        $this->assertInternalType('array', $result, 'test6');
        $this->assertArrayHasKey('categoryId', $result);
        $this->assertArrayHasKey('articleId', $result);
        $this->assertEquals(1, $result['categoryId']);
        $this->assertEquals(11, $result['articleId']);

        //Подаем на вход простой url c дополнительными паременными
        $result = $this->_routeStar->match('shop/1/11/order/price');
        $this->assertInternalType('array', $result, 'test7');
        $this->assertArrayHasKey('categoryId', $result);
        $this->assertArrayHasKey('articleId', $result);
        $this->assertArrayHasKey('order', $result);
        $this->assertEquals(1, $result['categoryId']);
        $this->assertEquals(11, $result['articleId']);
        $this->assertEquals('price', $result['order']);


        //Подаем на вход простой url для проверки статики
        $result = $this->_routeStatic->match('shop');
        $this->assertInternalType('array', $result, 'test8');
        $this->assertArrayHasKey('categoryId', $result);
        $this->assertEquals($this->_categoryId, $result['categoryId']);


        //Тест роутера с непоследовательными переменными
        $result = $this->_parterRoute->match('shop//articles/111');
        $this->assertInternalType('array', $result, 'test9');
        $this->assertArrayHasKey('categoryId', $result);
        $this->assertArrayHasKey('articleId', $result);
        $this->assertEquals($this->_categoryId, $result['categoryId']);
        $this->assertEquals(111, $result['articleId']);

        $route = $this->_routeDefault;
        $result = $route('default/testka/asdadas/ass');
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('testka', $result);
        $this->assertEquals('asdadas', $result['testka']);
    }
    
    public function testMisMatch()
    {
        $this->assertFalse($this->_route->match('pages'));
        $this->assertFalse($this->_route->match('pages/24/1'));
        $this->assertFalse($this->_routeStar->match('shop/1/ss'));
        $this->assertFalse($this->_routeStatic->match('shop/2'));
        $this->assertFalse($this->_parterRoute->match('shop/articles/25'));
        $this->assertFalse($this->_parterRoute->match('shop/21/articl/25'));
        $this->assertFalse($this->_parterRoute->match('shop/21/articles/25/ss'));
        $this->assertFalse($this->_parterRoute->match('shop/21/articles//'));
        $this->assertFalse($this->_routeEndStat->match('this_is_module/controller/index/2'));
    }
}