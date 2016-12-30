<?php

namespace Keystone\Tactician\RecordedEvent;

use Symfony\Component\EventDispatcher\Event;

class EventRecorder implements EventRecorderInterface
{
    /**
     * @var RecordedEvent[]
     */
    private $events = [];

    /**
     * @param string $name
     * @param Event  $event
     */
    public function record($name, Event $event)
    {
        $this->events[] = new RecordedEvent($name, $event);
    }

    /**
     * @return RecordedEvent[]
     */
    public function recordedEvents()
    {
        return $this->events;
    }

    public function eraseEvents()
    {
        $this->events = [];
    }
}
