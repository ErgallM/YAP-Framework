<?php
namespace Yap;

require_once 'Yap/Loader.php';

class View
{
    private $_encode = 'UTF-8';

    protected $_path = array(
        'helper'    => array(),
        'filter'    => array(),
        'script'    => array(),
        'form'      => array()
    );

    protected $_helpers = array();

    protected $_file = null;

    protected $_vars = array();

    public function __construct(array $options = array())
    {
        // Set path
        if (isset($options['path']) && is_array($options['path'])) {
            foreach ($options['path'] as $key => $values) {
                if (isset($this->_path[$key])) array_merge($this->_path[$key], (array) $values);
            }
        }

        if (isset($options['file'])) {
            $this->setRenderFile($options['file']);
        }

        if (isset($options['encode'])) {
            $this->_encode = (string) $options['encode'];
        }

        if (!empty($this->_path['helper'])) {
            \Yap\Loader::addPaths($this->_path['helper']);
        }
    }

    public function __set($name, $value)
    {
        $this->_vars[$name] = $value;
    }

    public function __get($name)
    {
        return (isset($this->_vars[$name])) ? $this->_vars[$name] : null;
    }

    public function setRenderFile($file)
    {
        if (!file_exists($file)) {
            throw new \Exception("Can't found file '" . $file . "'");
        }

        $this->_file = (string) $file;
    }

    public function render($file = null)
    {
        if (null !== $file) $this->setRenderFile($file);

        ob_start();

        include $this->_file;

        return ob_get_clean();
    }

    public function __call($name, $values = null)
    {
        $helperName = 'Yap\\View\\Helper\\' . $name;
        
        $helper = new $helperName($this);
        if (null !== $values) $helper->$name($values);
        return $helper;
    }
}
