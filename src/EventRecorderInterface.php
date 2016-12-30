<?php

namespace Keystone\Tactician\RecordedEvent;

use Symfony\Component\EventDispatcher\Event;

interface EventRecorderInterface
{
    /**
     * @param string $name
     * @param Event  $event
     */
    public function record($name, Event $event);

    /**
     * @return RecordedEvent[]
     */
    public function recordedEvents();

    public function eraseEvents();
}
