<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\Database\Statistics;
use Spiral\Tests\BaseTest;

class TrackTest extends BaseTest
{
    public function testEvent()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $track->event('some-event', 1.23);

        $this->assertCount(1, $this->orm->source(Statistics::class));
    }

    public function testEventInc()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23, $datetime);
        $track->event('some-event', 2.34, $datetime);

        $this->assertCount(1, $this->orm->source(Statistics::class));

        /** @var Statistics $event */
        $event = $this->orm->source(Statistics::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(3.57, $event->value);
    }

    public function testSignEventInc()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23, $datetime);
        $track->event('some-event', -2.34, $datetime);

        $this->assertCount(1, $this->orm->source(Statistics::class));

        /** @var Statistics $event */
        $event = $this->orm->source(Statistics::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(-1.11, $event->value);
    }

    public function testZeroEventInc()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23, $datetime);
        $track->event('some-event', 0, $datetime);

        $this->assertCount(1, $this->orm->source(Statistics::class));

        /** @var Statistics $event */
        $event = $this->orm->source(Statistics::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(1.23, $event->value);
    }

    public function testEvents()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $track->events([
            'some-event'  => 1.23,
            'some-event2' => 2.34
        ]);

        $this->assertCount(2, $this->orm->source(Statistics::class));
    }

    public function testEmptyEvents()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $track->events([]);

        $this->assertCount(0, $this->orm->source(Statistics::class));
    }

    public function testEventsInc()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23,
        ], $datetime);
        $track->events([
            'some-event' => 2.34,
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Statistics::class));

        /** @var Statistics $event */
        $event = $this->orm->source(Statistics::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(3.57, $event->value);
    }

    public function testSignEventsInc()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23,
        ], $datetime);
        $track->events([
            'some-event' => -2.34,
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Statistics::class));

        /** @var Statistics $event */
        $event = $this->orm->source(Statistics::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(-1.11, $event->value);
    }

    public function testZeroEventsInc()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23,
        ], $datetime);
        $track->events([
            'some-event' => 0,
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Statistics::class));

        /** @var Statistics $event */
        $event = $this->orm->source(Statistics::class)->findOne();

        $this->assertNotEmpty($event);
        $this->assertSame(1.23, $event->value);
    }
}