<?php
namespace Yap\Config;

class Xml extends \Yap\Config\Config
{
    const XML_NAMESPACE = 'http://yap.ncwlife.ru';

    public function __construct($xml, $selection = null)
    {
        $config = $this->_loadXmlFile($xml, $selection);
        parent::__construct($config);
    }
    
    private function _loadXmlFile($xml, $selector = null)
    {
        // Загрузка xml из строки или файла
        if ('<?xml' == substr($xml, 0, 5)) {
            $xmlContent = simplexml_load_string($xml);
        } else {
            $xmlContent = simplexml_load_file($xml);
        }

        // Проверяем $selector на существование
        if (null !== $selector && false === isset($xmlContent->$selector)) {
            throw new \Exception("Selection '$selector' not found in '$xml'");
        }

        // Взятие атрибутов в виде массива
        function getAttributes(\SimpleXmlElement $element)
        {
            $attr = (array) $element->attributes();
            return (isset($attr['@attributes'])) ? $attr['@attributes'] : array();
        }

        // Сращивание массивов
        function arrayMerge(array $array1, array $array2)
        {
            $result = $array1;
            foreach ($array2 as $key => $value) {
                if (is_array($value)) {
                    if (isset($array1[$key]) && is_array($array1[$key])) {
                        $result[$key] = arrayMerge($array1[$key], $value);
                    } else {
                        $result[$key] = $value;
                    }
                } else {
                    $result[$key] = $value;
                }
            }
            return $result;
        }

        // Парсинг элемента
        function parsetNode(\SimpleXmlElement $node, &$nodeName)
        {
            $attr = getAttributes($node);
            if (!empty($attr['name'])) {
                $nodeName = (string) $attr['name'];
                unset($attr['name']);
            }
            $nodeNameKey = (!empty($attr['key'])) ? (string) $attr['key'] : null;
            unset($attr['key']);

            $nodeValue = null;

            if (!$node->count()) {
                $nodeValue = (string) $node;
                
                if (!empty($attr['value'])) {
                    $nodeValue = (string) $attr['value'];
                    unset($attr['value']);
                }

                if (sizeof($attr)) {
                    $nodeValue = (array) $attr;
                }

                if (null !== $nodeNameKey) {
                    $nodeValue = array($nodeNameKey => $nodeValue);
                }
            } else {
                $nodeValue = array();
                foreach ($node->children() as $childrenNodeName => $childrenNode) {
                    $parserValue = parsetNode($childrenNode, $childrenNodeName);

                    if (!isset($nodeValue[$childrenNodeName])) {
                        $nodeValue[$childrenNodeName] = array();
                    }

                    if (is_array($parserValue)) {
                        $nodeValue[$childrenNodeName] = arrayMerge($nodeValue[$childrenNodeName], $parserValue);
                    } else {
                        $nodeValue[$childrenNodeName] = $parserValue;
                    }
                }
            }

            return $nodeValue;
        }

        $config = array();

        // Обход главного дерева
        foreach ($xmlContent as $nodeName => $node) {
            $attr = getAttributes($node);
            $extendNode = null;

            // Наследование об другой ветки
            if (!empty($attr['extends'])) {
                if (!isset($config[$attr['extends']])) {
                    throw new \Exception("Not found selector '{$attr['extends']}' in $xml for extends to '$nodeName' selector");
                }

                $extendNode = $config[$attr['extends']];
            }

            $parserNode = parsetNode($node, $nodeName);

            if (null === $extendNode) {
                $config[$nodeName] = $parserNode;
            } else {
                $config[$nodeName] = arrayMerge($extendNode, $parserNode);
            }
        }
        return $config;
    }
}
