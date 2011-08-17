<?php
namespace Yap\Config;

/**
 * @throws \Exception
 */
class Config implements \IteratorAggregate
{
    protected $_params = array();

    public function __set($name, $value)
    {
        $this->_params[$name] = $value;
    }

    public function __get($name)
    {
        if (!$this->__isset($name)) throw new \Exception("$name is not set");

        return $this->_params[$name];
    }

    public function __isset($name)
    {
        return isset($this->_params[$name]);
    }

    public function __unset($name)
    {
        unset($this->_params[$name]);
    }

    public function toArray()
    {
        return $this->_params;
    }

    public function __call($name, $params)
    {
        if (isset($this->_params[$name])) {
            return new Config($this->_params[$name]);
        }

        return null;
    }

    /**
     * Set values from array
     *
     * @param array $options
     * @return \Yap\Config\Config
     */
    public function setFromArray(array $options)
    {
        $this->_params = array();
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * @throws \Exception
     * @param null|string|array $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof Config) {
            $options = $options->toArray();
        }

        if (is_string($options) && file_exists($options)) {
            $fileInfo = pathinfo($options);

            switch ($fileInfo['extension']) {
                case 'xml': $file = new Xml($options);
                    $options = $file->toArray();
                    break;

                case 'ini': $file = new Ini($options);
                    $options = $file->toArray();
                    break;

                case 'php': $file = include($options);
                    if (!is_array($file)) throw new \Exception('Config file "' . $options . '" not include array, giving "' . gettype($file) . '"');
                    break;

                default: throw new \Exception('Config file can have xml or ini extension, giving "' . $fileInfo['extension'] . '"');
                    break;
            }
        }

        if (is_array($options) && sizeof($options)) {
            $this->setFromArray($options);
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->_params);
    }
}
