<?php
namespace Yap\Application;

class Dispatcher
{
    protected static $_instance = null;

    protected $_routeContainer = null;

    protected $_request = null;

    /**
     * @static
     * @return \Yap\Application\Dispatcher
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param \Yap\Router\Container $container
     * @return Dispatcher
     */
    public function setRouter(\Yap\Router\Container $container)
    {
        $this->_routeContainer = $container;
        return $this;
    }

    /**
     * @return \Yap\Router\Container
     */
    public function getRouter()
    {
        if (null === $this->_routeContainer) {
            $this->setRouter(new \Yap\Router\Container());
        }
        return $this->_routeContainer;
    }

    /**
     * @return \Yap\Application\Request
     */
    public function getRequest()
    {
        if (null === $this->_request) {
            $this->_request = new \Yap\Application\Request();
        }
        return $this->_request;
    }

    /**
     * @param \Yap\Application\Request $request
     * @return \Yap\Application\Dispatcherher
     */
    public function setRequest(\Yap\Application\Request $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * @return \Yap\Event\EventManager
     */
    public function getEventManager()
    {
        return \Yap\Event\EventManager::getInstance();
    }

    public function dispatch($url)
    {
        $this->createEvents();
        $this->getEventManager()->dispatcherStart();
    }

    protected function createEvents()
    {
        $dispatcher = $this;
        
        $this->getEventManager()
        
            // Запуска диспетчеризации
            ->addEvent('dispatcherStart', function() use ($dispatcher) {

            })

            // Запуск загрузки модулей
            ->getEvent('dispatcherStart')->append('dispatcherLoadModules', function() use ($dispatcher) {
                
            });
    }
}