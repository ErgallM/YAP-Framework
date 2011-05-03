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
            if (false !== ($params = $route->match($path))) {
                $params['routeName'] = $route->getName();

                return $params;
            }
        }

        return false;
    }
}
