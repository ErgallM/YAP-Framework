<?php
namespace Yap\Router;

abstract class RouteAbstract
{
    private $_name = '';
    private $_route = '';
    private $_reqs = array();
    private $_defaults = array();

    private $_static = '';

    private $_mask = false;
    private $_match = '';

    public function __construct($name, $route, $defaults = null, $reqs = null)
    {
        $this->_name = (string) $name;

        $this->_route = (string) $route;

        if (is_array($reqs) && sizeof($reqs)) {
            $this->_reqs = $reqs;
        }

        if (is_array($defaults) && sizeof($defaults)) {
            $this->_defaults = $defaults;
        }

        if (0 !== strpos($route, ':')) {
            $this->_static = substr($route, 0, strpos($route, ':'));
        }

        if (strlen($route) - 1 === strrpos($route, '*')) {
            $this->_route = substr($route, 0, strlen($route) - 2);
            $this->_mask = true;
        }

        $routeArray = explode('/', $this->_route);
        $matchArray = array();
        if (sizeof($routeArray)) {
            while (sizeof($routeArray)) {
                $var = array_shift($routeArray);

                if (0 === strpos($var, ':')) {
                    $var = substr($var, 1);
                    if (isset($this->_reqs[$var])) $var = $this->_reqs[$var];
                    else $var = '.*';

                } elseif ('*' === $var) {
                    $var = '.*';
                }

                if (true === $this->_mask && !sizeof($routeArray)) $var = $var . '$|' . $var . '\/.*';
                elseif (!sizeof($routeArray)) $var .= '$';

                $matchArray[] = "($var)";
            }
            $this->_match = '#^' . implode($matchArray, '\/') . '#i';
        }
    }

    public function match($path)
    {
        if (!empty($this->_static) && 0 !== strpos($path, $this->_static)) return false;

        echo "{route: '$this->_route'; path: '$path'; match: '$this->_match'} - ";

        $path = substr($path, strlen($this->_static));
        $router = substr($this->_route, strlen($this->_static));

        if ($path == $router) return $this->_defaults;

        if (!preg_match($this->_match, $path)) {
            return false;

        } else {
            $pathVars = explode('/', $path);
            $params = $this->_defaults;

            foreach (explode('/', $router) as $var) {
                if (0 === strpos($var, ':')) {
                    $var = substr($var, 1);
                    $value = array_shift($pathVars);
                    if (null === $value && isset($this->_defaults[$var])) $value = $this->_defaults[$var];

                    $params[$var] = $value;
                } else {
                    array_shift($pathVars);
                }
            }

            if (true === $this->_mask && sizeof($pathVars)) {
                $key = '';
                $value = '';
                while (sizeof($pathVars)) {
                    $key = array_shift($pathVars);
                    $value = array_shift($pathVars);

                    if (null === $key) break;

                    if (!isset($params[$key])) $params[$key] = $value;
                }
            }

            return $params;
        }
    }
}
