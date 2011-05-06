<?php
namespace Yap;

class Application
{
    /**
     * @var Application
     */
    static $_application = null;

    /**
     * @var \Yap\Config
     */
    protected $_configs = null;

    /**
     * @var \Yap\Router\Container
     */
    protected $_routerContainer = null;

    /**
     * @var \Yap\Controller\Dispatcher
     */
    protected $_dispatcher = null;

    /**
     * @static
     * @return Application
     */
    static function getApplication()
    {
        if (null === self::$_application) {
            self::$_application = new self();
        }

        return self::$_application;
    }

    public function getConfig($name = null)
    {
        if (null === $name) return $this->_configs;
        else return $this->_configs->$name;
    }

    public function setConfig(\Yap\Config $config)
    {
        $this->_configs = $config;
        return $this;
    }

    public function getRouterContainer()
    {
        if (null === $this->_routerContainer) {
            $this->_routerContainer = new \Yap\Router\Container();
        }
        return $this->_routerContainer;
    }

    public function setRouterContainer(\Yap\Router\Container $container)
    {
        $this->_routerContainer = $container;
        return $this;
    }

    public function getDispatcher()
    {
        if (null === $this->_dispatcher) {
            $this->_dispatcher = new \Yap\Controller\Dispatcher();
        }
        return $this->_dispatcher;
    }

    public function setDispatcher(\Yap\Controller\Dispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }

    public function bootstrap()
    {

    }

    public function bootstrapModule($moduleName)
    {
        
    }
}
