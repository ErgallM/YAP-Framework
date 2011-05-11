<?php
namespace Yap\Application;

abstract class ResourceAbstract
{
    protected $_config = null;

    protected $_application = null;

    public function __construct($options = null)
    {
        if (null !== $options) $this->setConfig($options);

        $this->init();
    }

    public function setConfig($config)
    {
        $this->_config = new \Yap\Config($config);
        return $this;
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function getApplication()
    {
        if (null === $this->_application) {
            $this->_application = \Yap\Application::getApplication();
        }

        return $this->_application;
    }

    public function init()
    {
        
    }
}