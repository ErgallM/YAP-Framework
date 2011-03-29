<?php
namespace \Yap\Controller;

class Front
{
    /**
     * @var \Yap\Controller\Front
     */
    protected static $_front = null;

    protected $_router = null;
    protected $_request = null;
    protected $_response = null;
    protected $_dispatcher = null;

    /**
     * Get front controller
     *
     * @static
     * @return \Yap\Controller\Front
     */
    public static function getFront()
    {
        if (null === self::$_front) {
            self::$_front = new self();
        }
        return self::$_front;
    }
}
