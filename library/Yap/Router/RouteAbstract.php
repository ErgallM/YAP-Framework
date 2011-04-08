<?php
namespace Yap\Router;

abstract class RouteAbstract
{
    private $_name = '';

    private $_route = '';

    private $_defaults = array();

    private $_reqs = array();

    private $_static = '';

    private $_match = '';

    private $_mask = false;


    public function __construct($name, $route, $defaults = null, $reqs = null)
    {
        $this->_name = (string) $name;

        $this->_route = (string) $route;

        if (is_array($defaults) && sizeof($defaults)) {
            $this->_defaults = $defaults;
        }

        if (is_array($reqs) && sizeof($reqs)) {
            $this->_reqs = $reqs;
        }

        if (false !== ($staticPos = strpos($route, ':'))) {
            $this->_static = substr($this->_route, 0, $staticPos);
            $route = substr($this->_route, $staticPos);
        }

        if ('*' == substr($route, strlen($route) - 1)) {
            $this->_mask = true;
            $route = substr($route, 0, strlen($route) - 2);
        }

        $routeVars = explode('/', $route);
        $matchArray = array();

        while (sizeof($routeVars)) {
            $var = array_shift($routeVars);
            if (':' == substr($var, 0, 1)) {
                $var = substr($var, 1);
                
                if (isset($this->_reqs[$var])) $var = $this->_reqs[$var];
                else $var = '.*';

            }

            $matchArray[] = '(' . $var . ')';
        }

        $this->_match = '#^' . implode($matchArray, '\/') . ((true === $this->_mask) ? '(?:(.*))' : '') . '$#i';
    }

    public function match($path)
    {
        if (!empty($this->_static) && 0 !== strpos($path, $this->_static)) return false;
        if ($path == $this->_static) return $this->_defaults;

        $path = substr($path, strlen($this->_static));

        echo "{match: '$this->_match'; path: '$path'} - ";

        if (!preg_match($this->_match, $path)) {
            return false;
        }

        $params = $this->_defaults;

        $routeVars = explode('/', substr($this->_route, strlen($this->_static)));
        $pathVars = explode('/', $path);

        while (sizeof($routeVars)) {
            $key = array_shift($routeVars);
            $value = array_shift($pathVars);

            if (':' == substr($key, 0, 1)) {
                $key = substr($key, 1);

                if (empty($value) && !empty($this->_defaults[$key])) $value = $this->_defaults[$key];
                $params[$key] = $value;
            }
        }

        if (true === $this->_mask && sizeof($pathVars)) {
            while (sizeof($pathVars)) {
                $key = array_shift($pathVars);
                $value = array_shift($pathVars);

                if (!isset($params[$key])) $params[$key] = $value;
            }
        }

        return $params;
    }
}
