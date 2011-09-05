<?php
namespace Yap\Event;

class Event
{
    protected $_name = '';

    protected $_event = null;

    protected $_append = array();
    protected $_prepend = array();

    const EVENT_APPEND = 'append';
    const EVENT_PREPEND = 'prepend';

    /**
     * @throws \Exception
     * @param string $name
     * @param callback|\Yap\Event\Event $event
     */
    public function __construct($name, $event)
    {
        if (!is_callable($event) && !$event instanceof Event) {
            throw new \Exception("Event must be Closure or Event object, giving '" . gettype($event) . "'");
        }

        if ($event instanceof Event) {
            $event = $event->getEvent();
        }

        $this->_name = (string) $name;
        $this->_event = $event;
    }

    /**
     * @return callback
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string|\Yap\Event\Event $name
     * @param null|callback|\Yap\Event\Event $event
     * @return Event
     */
    public function append($name, $event = null)
    {
        $eventManager = EventManager::getInstance();
        $eventManager->addEvent($name, $event);

        $this->_append[] = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getAppend()
    {
        return $this->_append;
    }

    /**
     * @param string|\Yap\Event\Event $name
     * @param null|callback|\Yap\Event\Event $event
     * @return Event
     */
    public function prepend($name, $event = null)
    {
        $eventManager = EventManager::getInstance();
        $eventManager->addEvent($name, $event);

        $this->_prepend[] = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getPrepend()
    {
        return $this->_prepend;
    }

    /**
     * @param array $arguments
     * @return void
     */
    public function exec(array $arguments = array())
    {
        $eventManager = EventManager::getInstance();

        foreach ($this->_prepend as $eventName) {
            $eventManager->$eventName();
        }

        $event = $this->_event;
        call_user_func_array($event, $arguments);

        foreach ($this->_append as $eventName) {
            $eventManager->$eventName();
        }
    }

    /**
     * @param array $argiments
     * @return void
     */
    public function __invoke(array $argiments = array())
    {
        $this->exec($argiments);
    }

    /**
     * @param $name
     * @param null|string $type
     * @return Event
     */
    public function deleteLink($name, $type = null)
    {
        if (self::EVENT_APPEND == $type || null == $type) {
            foreach ($this->_append as $key => $eventName) {
                if ($eventName == $name) unset($this->_append[$key]);
            }
        }

        if (self::EVENT_PREPEND == $type || null == $type) {
            foreach ($this->_prepend as $key => $eventName) {
                if ($eventName == $name) unset($this->_prepend[$key]);
            }
        }

        return $this;
    }

}