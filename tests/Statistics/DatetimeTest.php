<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Tests\BaseTest;

class DatetimeTest extends BaseTest
{
    /**
     * Default datetime is NOW for track event
     */
    public function testEventDatetime()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->event('some-event', 1.23);
        $track->event('some-event2', 2.34, $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $this->orm->source(Event::class));
    }

    /**
     * Default datetime is NOW for track events
     */
    public function testEventsDatetime()
    {
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime = new \DateTime('now');
        $track->events([
            'some-event' => 1.23
        ]);
        $track->events([
            'some-event2' => 2.34
        ], $datetime);

        $this->assertCount(1, $this->orm->source(Occurrence::class));
        $this->assertCount(2, $this->orm->source(Event::class));
    }
}