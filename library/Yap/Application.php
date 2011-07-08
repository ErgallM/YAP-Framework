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

    /**
     * Set application config
     *
     * @param Config $config
     * @return Application
     */
    public function setConfig(\Yap\Config\Config $config)
    {
        $this->_configs = $config;
        return $this;
    }

    /**
     * Get application config
     * 
     * @return null|Config
     */
    public function getConfig()
    {
        if (null === $this->_configs) {
            $this->_configs = new \Yap\Config\Config();
        }
        return $this->_configs;
    }
}
