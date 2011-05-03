<?php
namespace Yap\Controller;

class Action
{
    protected $_view = null;

    protected $_request = null;
    protected $_response = null;

    public function init() {}

    public function getView()
    {
        return $this->_view;
    }
}
