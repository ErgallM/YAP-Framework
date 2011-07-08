<?php
namespace Yap\Router;

class Container
{
    protected $_routes = array();
    protected $_routesKey = array();

    /**
     * Добавение роутера
     *
     * @param \Yap\Route\RouteInterface $route  Роутер
     * @return \Yap\Route\Conteiner
     */
    public function addRoute(RouteInterface $route)
    {
        $this->_routes[] = $route;
        $this->_routesKey[$route->getName()] = sizeof($this->_routes) - 1;

        return $this;
    }

    /**
     * Get route
     *
     * @param string $name
     * @return \Yap\Route\RouteInterface|null
     */
    public function getRoute($name)
    {
        return (!isset($this->_routesKey[$name])) ? null : $this->_routes[$this->_routesKey[$name]];
    }

    /**
     * Проверяет все роутеры на соответствие
     * Возвращает <b>false</b> при неудачи или
     * <b>array('routeName' => ...</b>
     *
     * @param  $path
     * @return array|false
     */
    public function match($path)
    {
        for ($x = sizeof($this->_routes); $x--; $x >= 0) {
            $route = $this->_routes[$x];
            if (null !== $route && false !== ($params = $route->match($path))) {
                $params['routeName'] = $route->getName();

                return $params;
            }
        }

        return false;
    }

    /**
     * Remove route
     *
     * @param string $name
     * @return \Yap\Router\Container
     */
    public function removeRoute($name)
    {
        if (isset($this->_routesKey[$name])) {
            $this->_routes[$this->_routesKey[$name]] = null;
            unset($this->_routesKey[$name]);
        }

        return $this;
    }

    /**
     * Add routes
     * 
     * @throws \Exception
     * @param \Yap\Config\Config|array $routes
     * @return \Yap\Router\Container
     */
    public function addRoutes($routes)
    {
        if ($routes instanceof \Yap\Config\Config) {
            $routes = $routes->toArray();
        }

        if (!is_array($routes)) throw new \Exception('$routes can be array or \Yap\Config\Config, givin "' . gettype($routes) . '"');

        foreach ($routes as $routeName => $routeData) {
            $this->addRoute(new Route(array_merge(array('name' => $routeName), $routeData)));
        }

        return $this;
    }
}
