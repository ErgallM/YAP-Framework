<?php
namespace Yap\Config;

class Xml extends \Yap\Config\Config
{
    const XML_NAMESPACE = 'http://yap.ncwlife.ru';

    /**
     * Ключ для индекса массива одноименных элементов
     */
    public static $_XML_ELEMENT_ARRAY_KEY_NAME = 'key';

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
        $getAttributes = function (\SimpleXmlElement $element)
        {
            $attr = (array) $element;
            return (isset($attr['@attributes'])) ? $attr['@attributes'] : array();
        };

        // Сращивание массивов
        $arrayMerge = function(array $array1, array $array2)
        {
            $result = $array1;
            foreach ($array2 as $key => $value) {
                if (is_array($value)) {
                    if (isset($array1[$key]) && is_array($array1[$key])) {
                        $result[$key] = $arrayMerge($array1[$key], $value);
                    } else {
                        $result[$key] = $value;
                    }
                } else {
                    $result[$key] = $value;
                }
            }
            return $result;
        };

        // Парсинг элемента
        $parsetNode = function(\SimpleXmlElement $node, &$nodeName) use($getAttributes)
        {
            $attr = $getAttributes($node);
            if (!empty($attr['name'])) {
                $nodeName = (string) $attr['name'];
                unset($attr['name']);
            }
            $nodeNameKey = (!empty($attr[\Yap\Config\Xml::$_XML_ELEMENT_ARRAY_KEY_NAME])) ? (string) $attr[\Yap\Config\Xml::$_XML_ELEMENT_ARRAY_KEY_NAME] : null;
            unset($attr[\Yap\Config\Xml::$_XML_ELEMENT_ARRAY_KEY_NAME]);

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

                $namespace = $node->getNamespaces(true);
                if (isset($namespace['yap'])) {
                    $yap = $node->children($namespace['yap']);

                    if (isset($yap->const)) {
                        $yapAttr = $getAttributes($yap->const);

                        if (!isset($yapAttr['name'])) throw new \Exception("Const can't have a name");

                        $constName = $yapAttr['name'];
                        if (defined($constName)) {
                            $defVars = \get_defined_constants();
                            $nodeValue .= $defVars[$constName];
                        } else {
                            $nodeValue .= $constName;
                        }
                    }
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
                        $nodeValue[$childrenNodeName] = $arrayMerge($nodeValue[$childrenNodeName], $parserValue);
                    } else {
                        $nodeValue[$childrenNodeName] = $parserValue;
                    }
                }
            }

            return $nodeValue;
        };

        $config = array();

        // Обход главного дерева
        foreach ($xmlContent as $nodeName => $node) {
            $attr = $getAttributes($node);
            $extendNode = null;

            // Наследование об другой ветки
            if (!empty($attr['extends'])) {
                if (!isset($config[$attr['extends']])) {
                    throw new \Exception("Not found selector '{$attr['extends']}' in $xml for extends to '$nodeName' selector");
                }

                $extendNode = $config[$attr['extends']];
            }

            // Определение ключей массива одноименных элементов
            $nodeNameKey = (!empty($attr[\Yap\Config\Xml::$_XML_ELEMENT_ARRAY_KEY_NAME])) ? (string) $attr[\Yap\Config\Xml::$_XML_ELEMENT_ARRAY_KEY_NAME] : null;
            unset($attr[\Yap\Config\Xml::$_XML_ELEMENT_ARRAY_KEY_NAME]);

            $parserNode = $parsetNode($node, $nodeName);

            if (null === $extendNode) {
                if (null != $nodeNameKey) {
                    if (!isset($config[$nodeName]) || !is_array($config[$nodeName])) $config[$nodeName] = array();
                    $config[$nodeName] = $arrayMerge($config[$nodeName], $parserNode);
                } else {
                    $config[$nodeName] = $parserNode;
                }
            } else {
                $config[$nodeName] = $arrayMerge($extendNode, $parserNode);
            }
        }
        return $config;
    }
}
