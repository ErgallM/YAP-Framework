<?php
namespace Yap\Config;

class Ini extends \Yap\Config
{
    public function __construct($ini)
    {
        $config = $this->_loadIniFile($ini);
        parent::__construct($config);
    }

    private function _loadIniFile($fileName)
    {
        $content = parse_ini_file($fileName, true);
        $config = array();

        function parserIniFileName($name, $value)
        {
            if (false === ($pos = strpos($name, ':'))) {
                return array($name => $value);

            } else {

                $nodeName = substr($name, 0, $pos - 1);
                $name = substr($name, $pos);
                return array($nodeName => parserIniFileName($name, $value));
            }
        };

        function parserIniFile($options) {
            $result = array();

            foreach ($options as $key => $value) {
                if (is_string($key) && is_string($value)) {
                    $result[$key] = $value;

                } else if (is_string($key) && is_array($value)) {
                    
                }

            }
        };

        $config = parserIniFile($content);

        return $config;
    }
}