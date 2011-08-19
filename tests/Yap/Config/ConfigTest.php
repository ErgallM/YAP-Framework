<?php
class ConfigTest extends PHPUnit_Framework_TestCase
{
    protected $_name = 'config testing';

    /**
     * @var \Yap\Config\Config
     */
    protected $_config;
    protected $_configData = array(
        'var1' => 'var 1 value',
        'var2' => 'var 2 value',
        'items' => array(
            'itemVar1' => 'item var 1 value',
            'itemVar2' => 'item var 2 value'
        ),
        'boolVar' => true
    );

    public function setUp()
    {
        parent::setUp();

        $this->_config = new \Yap\Config\Config($this->_configData);
    }

    public function testType()
    {
        $this->assertInstanceOf('\Yap\Config\Config', $this->_config);
    }

    public function testIssetUnsetVariable()
    {
        $this->assertTrue(isset($this->_config->boolVar));
        $this->assertFalse(isset($this->_config->falseVar));

    }
}