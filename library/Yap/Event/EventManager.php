<?php
namespace Yap\Event;

class EventManager
{
    protected static $_events = array();

    public function addEvent($name, $event)
    {
        if (!is_callable($event)) {
            throw new \Exception("Event '$name' isn't callable");
        }

        if (is_object($name)) {
            $name = str_replace(array('\\', '/', '_'), '-', $name);
        } else {
            $name = (string) $name;
        }

        self::$_events[$name] = $event;
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (isset(self::$_events[$name])) {
            $handel = self::$_events[$name];
            return call_user_func_array($handel, $arguments);
        }

        return null;
    }
}