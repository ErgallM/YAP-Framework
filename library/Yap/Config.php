<?php
namespace Yap;

/**
 * @throws \Exception
 */
class Config
{
    protected $_params = array();

    public function __set($name, $value)
    {
        $this->_params[$name] = $value;
    }

    public function __get($name)
    {
        if (!$this->__isset($name)) throw new \Exception("$name is not set");

        if (is_array($this->_params[$name])) return new Config($this->_params[$name]);
        else return $this->_params[$name];
    }

    public function __isset($name)
    {
        return isset($this->_params);
    }

    public function __unset($name)
    {
        unset($this->_params[$name]);
    }

    public function toArray()
    {
        return $this->_params;
    }

    /**
     * Set values from array
     *
     * @param array $options
     * @return \Yap\Config
     */
    public function setFromArray(array $options)
    {
        $this->_params = array();
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    public function __construct($options)
    {
        if (is_array($options) && !empty($options)) {
            $this->setFromArray($options);
        }
    }
}
