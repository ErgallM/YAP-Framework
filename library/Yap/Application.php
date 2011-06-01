<?php
namespace Yap;

class Application
{
    /**
     * @var \Yap\Application
     */
    public static $_application = null;

    /**
     * @var \Yap\Config
     */
    protected $_configs = null;

    /**
     * Get application
     * 
     * @static
     * @return \Yap\Application
     */
    public static function getApplication()
    {
        if (null === self::$_application) {
            self::$_application = new self();
        }
        return self::$_application;
    }

    public function setConfig(\Yap\Config $config)
    {
        $this->_configs = $config;
        return $this;
    }

    public function getConfig()
    {
        if (null === $this->_configs) {
            $this->_configs = new \Yap\Config();
        }
        return $this->_configs;
    }

    
    
}
