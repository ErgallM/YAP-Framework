<?php
namespace \Yap;

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

    public function __construct(array $options = array())
    {
        if (isset($options['path'])) {
            $paths = (array) $options['paths'];

            
        }


    }
}
