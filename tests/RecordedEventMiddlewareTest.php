<?php

namespace Keystone\Tactician\RecordedEvent;

use Exception;
use Keystone\Mockery\CallableMock;
use League\Tactician\Middleware;
use Mockery;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RecordedEventMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    private $recorder;
    private $dispatcher;
    private $middleware;

    public $nextSpy;

    public function setUp()
    {
        $this->recorder = new EventRecorder();
        $this->dispatcher = Mockery::mock(EventDispatcherInterface::class);

        $this->middleware = new RecordedEventMiddleware($this->recorder, $this->dispatcher);
    }

    public function testCallsNextWithCommand()
    {
        $command = new TestCommand();
        $next = new CallableMock();
        $next->shouldBeCalled()
            ->with($command)
            ->andReturn(true)
            ->once();

        $this->assertTrue($this->middleware->execute($command, $next));
    }

    public function testDispatchesEvents()
    {
        $command = new TestCommand();
        $next = new CallableMock();
        $next->shouldBeCalled()->once();

        $event1 = new Event();
        $this->recorder->record('event1', $event1);

        $event2 = new Event();
        $this->recorder->record('event2', $event2);

        $this->dispatcher->shouldReceive('dispatch')
            ->with('event1', $event1)
            ->once();

        $this->dispatcher->shouldReceive('dispatch')
            ->with('event2', $event2)
            ->once();

        $this->middleware->execute($command, $next);
    }

    public function testErasesEventsOnException()
    {
        $command = new TestCommand();
        $next = new CallableMock();
        $next->shouldBeCalled()
            ->andThrow(new Exception())
            ->once();

        $event1 = new Event();
        $this->recorder->record('event1', $event1);

        $event2 = new Event();
        $this->recorder->record('event2', $event2);

        $this->dispatcher->shouldReceive('dispatch')
            ->never();

        try {
            $this->middleware->execute($command, $next);
        } catch (Exception $exception) {
            $this->assertCount(0, $this->recorder->recordedEvents());
        }
    }

    /**
     * @expectedException \Exception
     */
    public function testThrowsException()
    {
        $command = new TestCommand();
        $next = new CallableMock();
        $next->shouldBeCalled()
            ->andThrow(new Exception())
            ->once();

        $this->middleware->execute($command, $next);
    }
}

class TestCommand
{
}
