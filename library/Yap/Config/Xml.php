<?php
namespace Yap\Config;

class Xml extends \Yap\Config\Config
{
    public function __construct($xml, $selection = null)
    {
        $config = $this->_loadXmlFile($xml, $selection);
        parent::__construct($config);
    }
    
    private function _loadXmlFile($xml, $selector = null)
    {
        $content = simplexml_load_file($xml);
        $config = array();

        if (null !== $selector && !$content->$selector) throw new \Exception("Can't found selector '$selector'");

        function parserNode(&$config, \SimpleXMLElement $node, $isArray = false) {
            $nodeName = $node->getName();
            $attributes = (array) $node->attributes();
            if (!empty($attributes)) $attributes = $attributes['@attributes'];

            $nowArray = (!empty($attributes['isArray'])) ? true : false;
            unset($attributes['isArray']);

            $nodeValue = trim((isset($attributes['value'])) ? (string) $attributes['value'] : (string) $node);
            unset($attributes['value']);

            if (!isset($config[$nodeName]) && $isArray) $config[$nodeName] = array();
            
            if (!empty($nodeValue)) {
                if ($isArray) $config[$nodeName][] = $nodeValue;
                else $config[$nodeName] = $nodeValue;

                return;
            }

            foreach ($attributes as $attrName => $attrValue) {
                if ('extends' == $attrName) {
                    if (!isset($config[$attrValue])) throw new \Exception("Can't found '$attrValue' selection");
                    $config[$nodeName] = $config[$attrValue];
                } else {
                    $config[$nodeName][$attrName] = $attrValue;
                }
            }

            if (!$node->count()) {
                if (!empty($nodeValue)) {
                    if ($isArray) {
                        $config[] = $nodeValue;
                    } else {
                        $config[$nodeName][] = $nodeValue;
                    }
                }
            } else {
                foreach ($node->children() as $child) {
                    parserNode($config[$nodeName], $child, $nowArray);
                }
            }
        };

        foreach ($content as $node) {
            parserNode($config, $node);

            if (null !== $selector && $node->getName() == $selector) {
                return $config[$selector];
            }
        }

        return $config;
    }
}
