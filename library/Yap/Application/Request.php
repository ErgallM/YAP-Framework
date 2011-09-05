<?php
namespace Yap\Application;

class Request
{
    protected $_params = array();

    public function getParams()
    {
        return $this->_params;
    }

    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    public function getParam($name, $default = null)
    {
        return (isset($this->_params[$name])) ? $this->_params[$name] : $default;
    }

    public function getPost($name, $default = null)
    {
        return (isset($_POST[$name])) ? $_POST[$name] : $default;
    }

    public function getGet($name, $default = null)
    {
        return (isset($_GET[$name])) ? $_GET[$name] : $default;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->_params);
    }

    public function getUrl()
    {
        return (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
    }
}