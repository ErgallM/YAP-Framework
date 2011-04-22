<?php
namespace Yap\Router;

use Yap\Filter as Filter;

abstract class RouteAbstract
{
    private $_name = '';
    private $_route = '';
    private $_defaults = array();
    private $_reqs = array();
    private $_static = null;
    private $_isStatic = false;

    public function __construct($name, $route, $defaults = null, $reqs = null)
    {
        $this->_name = $name;
        $this->_route = $route = trim($route, '/');
        if (is_array($defaults)) $this->_defaults = $defaults;
        if (is_array($reqs)) $this->_reqs = $reqs;

        if (false !== ($pos = strpos($route, ':'))) {
            $this->_static = trim(substr($route, 0, $pos), '/');
            $route = substr($route, strlen($this->_static));
        } else {
            if (false === strpos($route, '*')) {
                $this->_static = trim($route, '/');
                $this->_isStatic = true;
            }
        }
    }

    /**
     * Проверка на соответствие
     *
     * @param string $path
     * @return false|array
     */
    public function match($path)
    {
        $route = $this->_route;
        $path = trim($path, '/');

        // Если роутер полностью статичен
        if ($this->_isStatic) {
            if ($this->_static != $path) return false;
            
            return $this->_defaults;
        }

        // Проверка статичной части
        if (null !== $this->_static) {
            if (0 !== strpos($path, $this->_static)) return false;
            $route = trim(substr($route, strlen($this->_static)), '/');
            $path = trim(substr($path, strlen($this->_static)), '/');
        }

        $routeArray = explode('/', $route);
        $pathArray = explode('/', $path);
        $params = (is_array($this->_defaults)) ? $this->_defaults : array();

        $match = $matchPath = '';

        // Есть ли '*' в конце
        $last = false;

        while ($var = array_shift($routeArray)) {
            // Не извлекает часть из пути, есть последний символ роутера *
            if (!('*' == $var && !sizeof($routeArray)))
                $part = array_shift($pathArray);

            if (':' == substr($var, 0, 1)) {
                // $var is variable

                $varName = substr($var, 1);

                // Если для переменной нету значиня по умолчанию и кусок пути пустой
                if (!isset($this->_defaults[$varName]) && empty($part)) return false;

                if (isset($this->_reqs[$varName])) $var = $this->_reqs[$varName];
                else $var = '.*';

                // Если для переменной есть значение по умолчанию и кусок пути пустой
                if (isset($this->_defaults[$varName]) && empty($part)) $part = $this->_defaults[$varName];

                $params[$varName] = $part;
            } elseif ('*' == substr($var, 0, 1)) {
                if (!sizeof($routeArray)) {
                    $last = true;
                    $match .= (empty($match)) ? "$var" : "\/$var";
                    continue;
                }
                $var = '.*';
            } else {
                if ($var != $part) return false;
            }

            $match .= (empty($match)) ? "($var)" : "\/($var)";
            $matchPath .= (empty($matchPath)) ? "$part" : "/$part";
        }

        // Если есть дополнительные переменные, а их вводить нельзя
        if (sizeof($pathArray) && !$last) return false;

        $match = "#^$match$#i";

        if (preg_match($match, $matchPath)) {

            // Если остались переменные в пути, определяем их
            if (sizeof($pathArray)) {
                while (sizeof($pathArray)) {
                    $key = array_shift($pathArray);
                    $value = array_shift($pathArray);

                    // Дополнительными переменными нельзя сбить основные
                    if (!isset($params[$key])) $params[$key] = $value;
                }
            }

            // Возвращаем параметры
            return $params;
            
        } else {
            return false;
        }
    }
}
