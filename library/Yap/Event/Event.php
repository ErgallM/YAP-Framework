<?php
namespace Yap\Event;

class Event
{
    protected $_name = '';

    protected $_event = null;

    /**
     * @throws \Exception
     * @param string|\Yap\Config\Config|array $name
     * @param \Closure|null $event
     */
    public function __construct($name, \Closure $event = null)
    {
        if ($name instanceof \Yap\Config\Config) {
            $name = $name->toArray();
        }

        if (is_array($name)) {
            if (isset($name['event'])) {
                $event = $name['event'];
                unset($name['event']);
            }

            if (isset($name['name'])) {
                $name = $name['name'];
            }
        }

        if (!is_string($name)) throw new \Exception("Name can be string, give '" . gettype($name) . "'");
        if (!is_callable($event)) throw new \Exception("Event can be \\Closure, give '" . gettype($event) . "'");

        $this->_name = $name;
        $this->_event = $event;
    }
}