<?php
namespace Yap\Config;

class Xml extends \Yap\Config\Config
{
    const XML_NAMESPACE = 'http://yap.ncwlife.ru';

    /**
     * Ключ для индекса массива одноименных элементов
     */
    protected $_XML_ELEMENT_ARRAY_KEY_NAME = 'key';

    public function __construct($xml, $selection = null)
    {
        $config = $this->_loadXmlFile($xml, $selection);
        parent::__construct($config);
    }

    // Взятие атрибутов в виде массива
    private function getAttributes(\SimpleXmlElement $element)
    {
        $attr = (array) $element;
        return (isset($attr['@attributes'])) ? $attr['@attributes'] : array();
    }

    // Сращивание массивов
    private function arrayMerge(array $array1, array $array2)
    {
        $result = $array1;
        foreach ($array2 as $key => $value) {
            if (is_array($value)) {
                if (isset($array1[$key]) && is_array($array1[$key])) {
                    $result[$key] = $this->arrayMerge($array1[$key], $value);
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
    private function parseNode(\SimpleXmlElement $node, &$nodeName)
    {
        $attr = $this->getAttributes($node);
        if (!empty($attr['name'])) {
            $nodeName = (string) $attr['name'];
            unset($attr['name']);
        }
        $nodeNameKey = (!empty($attr[$this->_XML_ELEMENT_ARRAY_KEY_NAME])) ? (string) $attr[$this->_XML_ELEMENT_ARRAY_KEY_NAME] : null;
        unset($attr[$this->_XML_ELEMENT_ARRAY_KEY_NAME]);

        $nodeValue = null;

        if (!$node->count()) {

            $nodeValue = '';

            if ($node->children(self::XML_NAMESPACE)->count()) {
                $dom = dom_import_simplexml($node);

                foreach($dom->childNodes as $childrenNode) {
                    if ($childrenNode instanceof \DOMElement && $childrenNode->namespaceURI == self::XML_NAMESPACE) {
                        if ('const' == $childrenNode->localName && $childrenNode->hasAttributeNS(self::XML_NAMESPACE, 'name')) {
                            $constName = $childrenNode->getAttributeNS(self::XML_NAMESPACE, 'name');
                            if (!defined($constName)) {
                                throw new \Exception("Constant '{$constName}' was not defined");
                            }

                            $dom->replaceChild(new \DOMText(constant($constName)), $childrenNode);
                        }
                    }
                }
                $nodeValue = (string) simplexml_import_dom($dom);
            } else {
                if (isset($attr['value'])) {
                    $nodeValue = $attr['value'];
                    unset($attr['value']);
                } else {
                    $nodeValue = (string) $node;
                }
            }

            if (sizeof($attr)) {
                if (!empty($nodeValue)) {
                    $nodeValue = array('value' => $nodeValue);
                }
                foreach ($attr as $key => $value) {
                    $nodeValue[$key] = $value;
                }
            }

            if (null !== $nodeNameKey) {
                $nodeValue = array($nodeNameKey => $nodeValue);
            }

        } else {
            $nodeValue = array();
            foreach ($node->children() as $childrenNodeName => $childrenNode) {
                $parserValue = $this->parseNode($childrenNode, $childrenNodeName);

                if (!isset($nodeValue[$childrenNodeName])) {
                    $nodeValue[$childrenNodeName] = array();
                }

                if (is_array($parserValue)) {
                    $nodeValue[$childrenNodeName] = $this->arrayMerge($nodeValue[$childrenNodeName], $parserValue);
                } else {
                    $nodeValue[$childrenNodeName] = $parserValue;
                }
            }
        }

        return $nodeValue;
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

        $config = array();

        // Обход главного дерева
        foreach ($xmlContent as $nodeName => $node) {
            $attr = $this->getAttributes($node);
            $extendNode = null;

            // Наследование об другой ветки
            if (!empty($attr['extends'])) {
                if (!isset($config[$attr['extends']])) {
                    throw new \Exception("Not found selector '{$attr['extends']}' in $xml for extends to '$nodeName' selector");
                }

                $extendNode = $config[$attr['extends']];
            }

            // Определение ключей массива одноименных элементов
            $nodeNameKey = (!empty($attr[$this->_XML_ELEMENT_ARRAY_KEY_NAME])) ? (string) $attr[$this->_XML_ELEMENT_ARRAY_KEY_NAME] : null;
            unset($attr[$this->_XML_ELEMENT_ARRAY_KEY_NAME]);

            $parserNode = $this->parseNode($node, $nodeName);

            if (null === $extendNode) {
                if (null != $nodeNameKey) {
                    if (!isset($config[$nodeName]) || !is_array($config[$nodeName])) $config[$nodeName] = array();
                    $config[$nodeName] = $this->arrayMerge($config[$nodeName], $parserNode);
                } else {
                    $config[$nodeName] = $parserNode;
                }
            } else {
                $config[$nodeName] = $this->arrayMerge($extendNode, $parserNode);
            }
        }
        return $config;
    }
}
