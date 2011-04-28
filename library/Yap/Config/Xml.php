<?php
namespace Yap\Config;

class Xml extends \Yap\Config
{
    public function __construct($xml, $selection = null) {
        $config = $this->_loadXmlFile($xml, $selection);
        parent::__construct($config);
    }
    
    private function _loadXmlFile($xml, $selector = null)
    {
        $content = simplexml_load_file($xml);
        $config = array();

        function parserNode(&$config, \SimpleXMLElement $node) {
            $nodeName = $node->getName();
            $attributes = (array) $node->attributes();
            if (!empty($attributes)) $attributes = $attributes['@attributes'];

            $nodeValue = trim((isset($attributes['value'])) ? (string) $attributes['value'] : (string) $node);
            unset($attributes['value']);

            if (!isset($config[$nodeName])) $config[$nodeName] = array();

            foreach ($attributes as $attrName => $attrValue) {
                if ('extends' == $attrName) {
                    if (!isset($config[$attrValue])) throw new \Exception("Can't found '$attrValue' selection");
                    $config[$nodeName] = $config[$attrValue];
                } else {
                    $config[$nodeName][$attrName] = $attrValue;
                }
            }

            if (!$node->count()) {
                $config[$nodeName] = $nodeValue;
            } else {
                foreach ($node->children() as $child) {
                    parserNode($config[$nodeName], $child);
                }
            }
        };

        foreach ($content as $node) {
            parserNode($config, $node);
        }

        return $config;
    }
}
