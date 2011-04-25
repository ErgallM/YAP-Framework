<?php
namespace Yap\Router;

require_once 'RouteInterface.php';

class RouteStatic implements RouteInterface
{
    protected $_name = '';
    protected $_static = null;
    protected $_defaults = array();

    public function __construct($name, $route, $defaults = null)
    {
        $this->_name = $name;
        $this->_static = trim($route, '/');
        if (is_array($defaults)) $this->_defaults = $defaults;
    }

    public function match($path)
    {
        $path = trim($path, '/');
        if ($this->_static != $path) return false;
        return $this->_defaults;
    }

    public function getName()
    {
        return $this->_name;
    }
}
