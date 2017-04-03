<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Track;
use Spiral\Tests\BaseTest;

/**
 * For \Spiral\Statistics\Extract::events tests see below:
 *
 * @see Spiral\Tests\Statistics\Extract\Intervals\AbstractInterval::testExtractEvents
 */
class ExtractTest extends BaseTest
{
    /**
     * @expectedException \Spiral\Statistics\Exceptions\InvalidExtractException
     */
    public function testEmptyEvents()
    {
        $extract = $this->getExtract();
        $extract->events(new \DateTime(), new \DateTime(), 'range', []);
    }

    public function testEvents()
    {
        $extract = $this->getExtract();
        $events = $extract->events(
            new \DateTime(),
            new \DateTime(),
            Extract\Range::DAILY, ['event']
        );

        $this->assertInstanceOf(Extract\Events::class, $events);
    }

    public function testDateSwap()
    {
        $extract = $this->getExtract();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $datetime1 = new \DateTime('now');
        $datetime2 = new \DateTime('tomorrow');

        $track->event('event1', 1, $datetime1);
        $track->event('event2', 1, $datetime1);
        $track->event('event2', 2, $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(3, $this->orm->source(Event::class));

        $start = new \DateTime('-2 days');
        $end = new \DateTime('+2 days');

        $this->assertEquals(
            $extract->events($start, $end, Extract\Range::DAILY, ['event1']),
            $extract->events($end, $start, Extract\Range::DAILY, ['event1'])
        );
    }
}