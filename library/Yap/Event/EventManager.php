<?php
namespace Yap\Event;

class EventManager
{
    protected static $_instance = null;

    protected $_events = array();

    /**
     * @static
     * @return \Yap\Event\EventManager
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @throws \Exception
     * @param string|\Yap\Event\Event $name
     * @param null|callback|\Yap\Event\Event $event
     * @return \Yap\Event\EventManager
     */
    public function addEvent($name, $event = null)
    {
        if ($name instanceof Event) {
            $event = $name;
            $name = $event->getName();
        }

        if (!is_callable($event) && !$event instanceof Event) {
            throw new \Exception("Event must be Closure or Event object, giving '" . gettype($event) . "'");
        }

        if (is_callable($event)) {
            $event = new Event((string) $name, $event);
        }

        $this->_events[$name] = $event;
        return $this;
    }

    /**
     * @param string $name
     * @return null|\Yap\Event\Event
     */
    public function getEvent($name)
    {
        if (isset($this->_events[$name])) {
            return $this->_events[$name];
        }
        return null;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call($name, array $arguments = array())
    {
        $event = $this->getEvent($name);
        if ($event instanceof Event) {
            $event($arguments);
        }
    }

    /**
     * @param string $name
     * @return null|\Yap\Event\Event
     */
    public function __get($name)
    {
        return (isset($this->_events[$name])) ? $this->_events[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return (bool) isset($this->_events[$name]);
    }
}