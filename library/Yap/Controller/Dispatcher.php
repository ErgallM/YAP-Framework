<?php
namespace Yap\Controller;

class Dispatcher
{
    /**
     * @var \Yap\Router\Container
     */
    protected $_routerContainer = null;

    protected $_modulesPath = '';

    protected $_defaults = array(
        'module'        => 'default',
        'controller'    => 'index',
        'action'        => 'index'
    );

    public function setDefaults($options)
    {
        if ($options instanceof \Yap\Config) {
            $options = $options->toArray();
        }

        if (!is_array($options)) throw new \Exception('Options can be array or \Yap\Config, giving "' . gettype($options) . '"');

        $this->_defaults = array_merge($this->_defaults, $options);

        return $this;
    }

    public function getDefaults()
    {
        return $this->_defaults;
    }

    public function setRouterContainer(\Yap\Router\Container $routerContainer)
    {
        $this->_routerContainer = $routerContainer;
        return $this;
    }

    public function getRouterContainer()
    {
        return $this->_routerContainer;
    }

    public function setModulePath($path)
    {
        $this->_modulesPath = $path;
        return $this;
    }

    public function getModulePath()
    {
        return $this->_modulesPath;
    }

    public function dispatch($path)
    {
        $params = $this->getRouterContainer()->match($path);
        if (false === $params) {
            throw new \Exception("Page not found", 404);
        }

        $params = array_merge($this->getDefaults(), $params);

        $controllerName = '\\' . $params['module'] . '\\' . $params['controller'];
        \Yap\Loader::loadClass($controllerName, $this->getModulePath());
        $controller = new $controllerName;

        var_dump($controller);
        
    }
}
