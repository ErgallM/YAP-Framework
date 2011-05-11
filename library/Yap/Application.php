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

    protected $_resources = array();
    protected $_resourcesKey = array();

    protected $_env = 'product';

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

    /**
     * Get application Config
     *
     * @param null $name
     * @return Config
     */
    public function getConfig($name = null)
    {
        if (null == $this->_configs) {
            $this->_configs = new \Yap\Config();
        }
        $config = $this->_configs;

        if (null === $name) return $config;
        else return $config->$name;
    }

    /**
     * Set application configs
     *
     * @param Config $config
     * @return Application
     */
    public function setConfig(\Yap\Config $config)
    {
        $this->_configs = $config;
        return $this;
    }

    /**
     * Get application router container
     *
     * @return Yap\Router\Container
     */
    public function getRouterContainer()
    {
        if (null === $this->_routerContainer) {
            $this->_routerContainer = new \Yap\Router\Container();
        }
        return $this->_routerContainer;
    }

    /**
     * Set application router container
     *
     * @param Router\Container $container
     * @return Application
     */
    public function setRouterContainer(\Yap\Router\Container $container)
    {
        $this->_routerContainer = $container;
        return $this;
    }

    /**
     * Get application dispatcher
     *
     * @return null|Controller\Dispatcher
     */
    public function getDispatcher()
    {
        if (null === $this->_dispatcher) {
            $this->_dispatcher = new \Yap\Controller\Dispatcher();
        }
        return $this->_dispatcher;
    }

    /**
     * Set application dispatcher
     *
     * @param Controller\Dispatcher $dispatcher
     * @return Application
     */
    public function setDispatcher(\Yap\Controller\Dispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }

    /**
     * Start bootstrap resources
     * @return void
     */
    public function bootstrap()
    {

    }

    /**
     * Bootstrap module bootstraper
     * 
     * @param  $moduleName
     * @return void
     */
    public function bootstrapModule($moduleName)
    {
        
    }

    public function setApplicationEnv($env)
    {
        if (!empty($env)) {
            $this->_env = $env;
        }

        return $this;
    }

    public function getApplicationEnv()
    {
        return $this->_env;
    }

    public function run()
    {
        $config = $this->getConfig();

        if (isset($config->resources) && is_array($config->resources)) {
            foreach ($config->resources as $resourceName => $resourceData) {
                $this->loadResources($resourceName, $resourceData);
            }
        }

        return $this;
    }

    public function __construct($options = null, $env = 'product')
    {
        $this->setApplicationEnv($env);

        if (is_string($options)) {
            $optionsExt = \pathinfo($options, PATHINFO_EXTENSION);
            if ('ini' == $optionsExt) $options = new \Yap\Config\Ini($options);
            elseif ('xml' == $optionsExt) $options = new \Yap\Config\Xml($options);
            else throw new \Exception("Not support '$optionsExt' extension for config file. Used 'ini' or 'xml' file");

        } else if ($options instanceof \Yap\Config) {
        } else throw new \Exception("'$options' is not \Yap\Config object or config file name");

        $this->setConfig($options->$env());
    }

    public function loadResources($name, array $options)
    {
        $resourceName = '\\Yap\\Application\\Resource\\' . $name;
        $resource = new $resourceName($options);

        $this->_resources[] = $resource;
        $this->_resourcesKey[$name] = sizeof($this->_resources) - 1;

        return $resource;
    }
}
