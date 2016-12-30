<?php

namespace Keystone\Tactician\RecordedEvent;

use Exception;
use League\Tactician\Middleware;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RecordedEventMiddleware implements Middleware
{
    /**
     * @var EventRecorderInterface
     */
    private $recorder;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param EventRecorderInterface   $recorder
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventRecorderInterface $recorder, EventDispatcherInterface $dispatcher)
    {
        $this->recorder = $recorder;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        try {
            $returnValue = $next($command);
        } catch (Exception $exception) {
            $this->recorder->eraseEvents();

            throw $exception;
        }

        foreach ($this->recorder->recordedEvents() as $event) {
            $this->dispatcher->dispatch($event->getName(), $event->getEvent());
        }

        return $returnValue;
    }
}
