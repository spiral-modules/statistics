<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Track;
use Spiral\Tests\BaseTest;

class TrackTest extends BaseTest
{
    public function testEvent()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $track->event('some-event', 1.23);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));
    }

    public function testEvents()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $track->events([
            'some-event'  => 1.23,
            'some-event2' => 2.34
        ]);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $this->orm->source(Event::class));
    }

    public function testEventInc()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23, $datetime);
        $track->event('some-event', 2.34, $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Event $event */
        $event = $this->orm->source(Event::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(3.57, $event->value);
    }

    public function testEventsInc()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23,
        ], $datetime);
        $track->events([
            'some-event' => 2.34,
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Event $event */
        $event = $this->orm->source(Event::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(3.57, $event->value);
    }

    public function testSignEventInc()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23, $datetime);
        $track->event('some-event', -2.34, $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Event $event */
        $event = $this->orm->source(Event::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(-1.11, $event->value);
    }

    public function testSignEventsInc()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23,
        ], $datetime);
        $track->events([
            'some-event' => -2.34,
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Event $event */
        $event = $this->orm->source(Event::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(-1.11, $event->value);
    }

    public function testZeroEventInc()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23, $datetime);
        $track->event('some-event', 0, $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Event $event */
        $event = $this->orm->source(Event::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(1.23, $event->value);
    }

    public function testZeroEventsInc()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23,
        ], $datetime);
        $track->events([
            'some-event' => 0,
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Event $event */
        $event = $this->orm->source(Event::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(1.23, $event->value);
    }

    public function testNewEventRelation()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23, $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Occurrence $occurrence */
        $occurrence = $this->orm->source(Occurrence::class)->findOne();
        $this->assertCount(1, $occurrence->events);

        $track->event('some-event2', 2.34, $datetime);

        /** @var Occurrence $occurrence */
        $occurrence = $this->orm->source(Occurrence::class)->findOne();
        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $this->orm->source(Event::class));
        $this->assertCount(2, $occurrence->events);
    }

    public function testNewEventsRelation()
    {
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23,
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(1, $this->orm->source(Event::class));

        /** @var Occurrence $occurrence */
        $occurrence = $this->orm->source(Occurrence::class)->findOne();
        $this->assertCount(1, $occurrence->events);
        $track->events([
            'some-event2' => 2.34,
        ], $datetime);

        /** @var Occurrence $occurrence */
        $occurrence = $this->orm->source(Occurrence::class)->findOne();
        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $this->orm->source(Event::class));
        $this->assertCount(2, $occurrence->events);
    }
}