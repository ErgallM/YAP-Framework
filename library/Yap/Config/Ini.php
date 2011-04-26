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

        function parserNodeName($name, $value)
        {
            echo $name . PHP_EOL;

            if (false === ($pos = strpos($name, '.'))) {
                if ('[]' == $value) {
                    $value = array();
                } else if ('[' == substr($value, 0, 1) && ']' == substr($value, -1)) {
                    $value = explode(',', substr($value, 1, -1));
                }
                
                return array($name => $value);
            } else {
                $nodeName = substr($name, 0, $pos);
                $name = substr($name, $pos + 1);
                return array($nodeName => parserNodeName($name, $value));
            }
        };

        foreach ($content as $nodeName => $nodeValue) {
            $nodeData = array();

            if (is_string($nodeName) && is_string($nodeValue)) {
                $nodeData = parserNodeName($nodeName, $nodeValue);

            } elseif (is_string($nodeName) && is_array($nodeValue)) {

                if (false !== ($pos = strpos($nodeName, ':'))) {
                    $parentNodeName = substr($nodeName, $pos + 1);
                    $nodeName = substr($nodeName, 0, $pos);

                    if (!isset($config[$parentNodeName])) throw new \Exception("Can't found section '$parentNodeName'");
                    $nodeData[$nodeName] = $config[$parentNodeName];
                }

                if (!isset($nodeData[$nodeName])) $nodeData[$nodeName] = array();

                foreach ($nodeValue as $key => $value) {
                    $nodeData[$nodeName] = array_merge_recursive($nodeData[$nodeName], parserNodeName($key, $value));
                }
            }

            $config = array_merge($config, $nodeData);
        }

        return $config;
    }
}