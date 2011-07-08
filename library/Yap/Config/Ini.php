<?php
namespace Yap\Config;

/**
 * Загрузка настоек из ini файлов
 * 
 * @throws \Exception
 */
class Ini extends \Yap\Config\Config
{
    public function __construct($ini, $selector = null)
    {
        $config = $this->_loadIniFile($ini, $selector);
        parent::__construct($config);
    }

    private function _loadIniFile($fileName, $selector = null)
    {
        $content = parse_ini_file($fileName, true);
        $config = array();

        // Проверка на существование selector'а
        if (null !== $selector) {
            $foundSelector = false;
            foreach ($content as $nodeName => $nodeValue) {
                if (false === ($pos = strpos($nodeName, ':'))) {
                    if ($nodeName == $selector) $foundSelector = true;
                } else {
                    if (substr($nodeName, 0, $pos) == $selector) $foundSelector = true;
                }
            }
            if (false === $foundSelector) throw new \Exception("Can't found selector '$selector'");
        }

        // Парсинг строки элементов
        function parserNodeName(&$config, $nodeName, $nodeValue)
        {
            if (false === ($pos = strpos($nodeName, '.'))) {

                // Преобразовываем значение [...] в массив
                if (is_string($nodeValue)) {
                    if ('[' == substr($nodeValue, 0, 1) && ']' == substr($nodeValue, -1)) {
                        $nodeValue = (array) explode(',', substr($nodeValue, 1, -1));
                    }
                }

                $config[$nodeName] = $nodeValue;
            } else {
                $node = substr($nodeName, $pos + 1);
                $nodeName = substr($nodeName, 0, $pos);

                if (!isset($config[$nodeName])) $config[$nodeName] = array();

                parserNodeName($config[$nodeName], $node, $nodeValue);
            }
        }

        foreach ($content as $nodeName => $nodeValue) {
            // Наследование элементов
            if (false !== ($pos = strpos($nodeName, ':'))) {
                $parentName = substr($nodeName, $pos + 1);
                $nodeName = substr($nodeName, 0, $pos);

                if (!isset($config[$parentName])) throw new \Exception("Can't found '$parentName' selection");
                $config[$nodeName] = $config[$parentName];
            }

            if (is_string($nodeName) && is_string($nodeValue)) {
                parserNodeName($config, $nodeName, $nodeValue);
            } else if (is_string($nodeName) && is_array($nodeValue)) {
                if (!isset($config[$nodeName])) $config[$nodeName] = array();

                foreach ($nodeValue as $key => $value) {
                    parserNodeName($config[$nodeName], $key, $value);
                }
            }

            // Если нужно вернуть selector
            if (null !== $selector && $nodeName == $selector) {
                return $config[$nodeName];
            }
        }

        return $config;
    }
}