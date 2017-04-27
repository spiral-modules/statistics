<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\Database\Statistics;
use Spiral\Statistics\Extract;
use Spiral\Tests\BaseTest;
use Spiral\Tests\Statistics\Entities\UnknownRange;

/**
 * For \Spiral\Statistics\Extract::events tests see below:
 *
 * @see Spiral\Tests\Statistics\Extract\Intervals\AbstractInterval::testSamePeriodExtractEvents
 * @see Spiral\Tests\Statistics\Extract\Intervals\AbstractInterval::testAnotherPeriodExtractEvents
 */
class ExtractTest extends BaseTest
{
    /**
     * @expectedException \Spiral\Statistics\Exceptions\InvalidExtractException
     */
    public function testEmptyEvents()
    {
        $extract = $this->getExtract();
        $extract->events(new \DateTime(), new \DateTime(), new UnknownRange(), []);
    }

    public function testEvents()
    {
        $extract = $this->getExtract();
        $events = $extract->events(
            new \DateTime(),
            new \DateTime(),
            new Extract\Range\DailyRange(), ['event']
        );

        $this->assertInstanceOf(Extract\Events::class, $events);
    }

    public function testDateSwap()
    {
        $extract = $this->getExtract();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime1 = new \DateTime('now');
        $datetime2 = new \DateTime('tomorrow');

        $track->event('event1', 1, $datetime1);
        $track->event('event2', 1, $datetime1);
        $track->event('event2', 2, $datetime2);

        $this->assertCount(3, $this->orm->source(Statistics::class));

        $start = new \DateTime('-2 days');
        $end = new \DateTime('+2 days');

        $range = new Extract\Range\DailyRange();

        $this->assertEquals(
            $extract->events($start, $end, $range, ['event1']),
            $extract->events($end, $start, $range, ['event1'])
        );
    }

    public function testFillGaps()
    {
        $extract = $this->getExtract();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $datetime1 = new \DateTime('now');
        $datetime2 = new \DateTime('tomorrow');

        $track->event('event1', 1, $datetime1);
        $track->event('event2', 1, $datetime1);
        $track->event('event2', 2, $datetime2);

        $this->assertCount(3, $this->orm->source(Statistics::class));

        $start = new \DateTime('-2 days');
        $end = new \DateTime('+2 days');

        $range = new Extract\Range\DailyRange();
        $events = $extract->events($start, $end, $range, ['event1']);

        $this->assertCount(5, $events->getRows());
    }
}