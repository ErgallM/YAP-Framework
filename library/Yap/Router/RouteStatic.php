<?php
namespace Yap\Router;

require_once 'RouteInterface.php';

class RouteStatic implements RouteInterface
{
    protected $_name = '';
    protected $_route = '';
    protected $_defaults = array();

    /**
     * @throws \Exception
     * @param \Yap\Config\Config|array $options
     */
    public function __construct($options)
    {
        if ($options instanceof \Yap\Config\Config) {
            $options = $options->toArray();
        }

        if (!is_array($options)) throw new \Exception('Options can be array or \Yap\Config\Config, giving "' . gettype($options) . '"');

        $this->setName($options['name'])
             ->setRoute($options['route']);

        if (isset($options['defaults']) && is_array($options['defaults'])) $this->setDefaults($options['defaults']);
    }

    public function match($path)
    {
        $path = trim($path, '/');

        if ($this->_route == $path) return $this->_defaults;

        return false;
    }

    /**
     * Get route name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set route name
     * 
     * @param string $name
     * @return \Yap\Router\RouteStatic
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Set default variable
     *
     * @param array $defaults
     * @return \Yap\Router\RouteStatic
     */
    public function setDefaults(array $defaults)
    {
        $this->_defaults = $defaults;
        return $this;
    }

    /**
     * Get default variable
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->_defaults;
    }

    /**
     * Set route string
     *
     * @param string $route
     * @return \Yap\Router\RouteStatic
     */
    public function setRoute($route)
    {
        $this->_route = trim($route, '/');
        return $this;
    }

    /**
     * Get route string
     * 
     * @return string
     */
    public function getRoute()
    {
        return $this->_route;
    }

    public function __invoke($path)
    {
        return $this->match($path);
    }
}
