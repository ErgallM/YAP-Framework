<?php
namespace Yap\Router;

abstract class RouteAbstract
{
    /**
     * Route name
     * @var string
     */
    private $_name = '';

    /**
     * Route url mask
     * @var string
     */
    private $_route = '';

    /**
     * Static path of route
     * @var string
     */
    private $_static = '';

    /**
     * Default values
     * @var array
     */
    private $_defaults = array();

    /**
     * Request of values
     * @var array
     */
    private $_reqs = array();

    /**
     * Match request
     * @var string
     */
    private $_match = '';

    /**
     * @param string $name
     * @param string $route
     * @param array|null $defaults
     * @param array|null $reqs
     */
    public function __construct($name, $route, $defaults = null, $reqs = null)
    {
        $this->_name = (string) $name;

        // Generete static path of route
        $this->_static = (string) substr($route, 0, strpos($route, ':'));

        // Set default values
        if (is_array($defaults) && sizeof($defaults)) {
            $this->_defaults = $defaults;
        }

        // Set request values
        if (is_array($reqs) && sizeof($reqs)) {
            $this->_reqs = $reqs;
        }
    }

    /**
     * Match
     * Return true, if url is match of match string
     *
     * @param  $url
     * @return bool
     */
    public function match($url)
    {
        if (!empty($this->_static) && 0 !== strpos($url, $this->_static)) return false;

        
    }
}