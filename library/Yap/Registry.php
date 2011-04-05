<?php
namespace Yap;

/**
 * Registry of variable
 */
class Registry
{
    static private $_vars = array();

    /**
     * Registry variable
     *
     * @static
     * @param string $name
     * @param mixed $value
     * @return void
     */
    static public function set($name, $value)
    {
        self::$_vars[$name] = $value;
    }

    /**
     * Get registry variable
     *
     * @static
     * @param string $name
     * @return mixed
     */
    static public function get($name)
    {
        return self::$_vars[$name];
    }

    /**
     * See registred variable
     *
     * @static
     * @param string $name
     * @return bool
     */
    static public function isRegistered($name)
    {
        return isset(self::$_vars[$name]);
    }
}
