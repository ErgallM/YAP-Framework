<?php
namespace Yap\Config;

class Xml extends \Yap\Config
{
    public function __construct($xml, $section = null)
    {
        if (empty($xml)) {
            throw new \Exception("Filename is not set");
        }

        if (strstr($xml, '<?xml')) {
            $config = simplexml_load_string($xml);
        } else {
            $config = simplexml_load_file($xml);
        }

        if (null === $section) {
            $result = array();
            foreach ($config as $name => $values) {
                $result[$name] = $values;
            }

            parent::__construct($result);
        } else {
            if (!isset($config->$section)) {
                throw new \Exception("Section '$section' not found in $xml");
            }

            parent::__construct($$config->$section);
        }
    }
}
