<?php
class ConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Yap\Config
     */
    protected $_config;

    public function setUp()
    {
        parent::setUp();

        $this->_config = new \Yap\Config(array(
                                              'module' => 'default',
                                              'controller' => 'index',
                                              'action' => 'index'
                                         ));
    }

    public function testMagicFunction()
    {
        $this->assertEquals('default', $this->_config->module);
        $this->_config->controller = 'test';
        $this->assertEquals('test', $this->_config->controller);

        unset($this->_config->controller);
        $this->assertFalse(isset($this->_config->controller));
    }

    public function testSetFromArray()
    {
        $this->_config->setFromArray(array('var' => 'test'));
        $this->assertEquals(array('var' => 'test'), $this->_config->toArray());
    }
}