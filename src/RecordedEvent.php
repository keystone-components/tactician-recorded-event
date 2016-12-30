<?php

namespace Keystone\Tactician\RecordedEvent;

use Symfony\Component\EventDispatcher\Event;

class RecordedEvent
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Event
     */
    private $event;

    /**
     * @param string $name
     * @param Event  $event
     */
    public function __construct($name, Event $event)
    {
        $this->name = $name;
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
