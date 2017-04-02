<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Database\Sources\OccurrenceSource;
use Spiral\Statistics\DatetimeConverter;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Track;

class Weekly extends AbstractInterval
{
    const RANGE          = Extract\Range::WEEKLY;
    const RANGE_FIELD    = 'week_mark';
    const RANGE_INTERVAL = 'P7D';
    const RANGE_FORMAT   = 'W, Y';

    protected function start(): \DateTime
    {
        return new \DateTime('today noon - 22 days');
    }

    protected function end(): \DateTime
    {
        return new \DateTime('today noon + 22 days');
    }

    protected function datetime1(): \DateTime
    {
        return new \DateTime('today noon');
    }

    protected function datetime2(): \DateTime
    {
        return new \DateTime('today noon + 2 hours');
    }

    protected function datetime3(): \DateTime
    {
        return new \DateTime('today noon + 8 days + 2 hours');
    }


//    public function testWeekly()
//    {
//        $rangeValue = Extract\Range::WEEKLY;
//        /** @var Extract $extract */
//        $extract = $this->container->get(Extract::class);
//        /** @var Track $track */
//        $track = $this->container->get(Track::class);
//
//        $datetime = new \DateTime('this Monday noon');
//        $datetime2 = (new \DateTime('this Monday noon'))->add(new \DateInterval('P1D'));
//
//        $this->assertCount(0, $this->orm->source(Occurrence::class));
//        $this->assertCount(0, $this->orm->source(Event::class));
//
//        $track->events([
//            'event1' => 1,
//            'event2' => 2
//        ], $datetime);
//
//        $track->events([
//            'event1' => 3,
//            'event2' => 4
//        ], $datetime2);
//
//        $this->assertCount(2, $this->orm->source(Occurrence::class));
//        $this->assertCount(4, $this->orm->source(Event::class));
//
//        //test start same date
//        $start = new \DateTime('this Monday noon');
//        $end = (new \DateTime('this Monday noon'))->add(new \DateInterval('P21D'));
//        $range = new Extract\Range($rangeValue);
//        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);
//
//        $label = $start->format($range->getFormat());
//        $this->assertArrayHasKey($label, $results->results());
//        $this->assertArrayHasKey('event1', $results->results()[$label]);
//        $this->assertArrayHasKey('event2', $results->results()[$label]);
//        $this->assertEquals(4, $results->results()[$label]['event1']);
//        $this->assertEquals(6, $results->results()[$label]['event2']);
//
//        //test start end date
//        $start = (new \DateTime('this Monday noon'))->sub(new \DateInterval('P21D'));
//        $end = new \DateTime('this Monday noon');
//        $range = new Extract\Range($rangeValue);
//        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);
//
//        $label = $end->format($range->getFormat());
//        $this->assertArrayHasKey($label, $results->results());
//        $this->assertArrayHasKey('event1', $results->results()[$label]);
//        $this->assertArrayHasKey('event2', $results->results()[$label]);
//        $this->assertEquals(4, $results->results()[$label]['event1']);
//        $this->assertEquals(6, $results->results()[$label]['event2']);
//
//        //test same start and end date
//        $start = $end = new \DateTime('this Monday noon');
//        $range = new Extract\Range($rangeValue);
//        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);
//
//        $label = $end->format($range->getFormat());
//        $this->assertArrayHasKey($label, $results->results());
//        $this->assertArrayHasKey('event1', $results->results()[$label]);
//        $this->assertArrayHasKey('event2', $results->results()[$label]);
//        $this->assertEquals(4, $results->results()[$label]['event1']);
//        $this->assertEquals(6, $results->results()[$label]['event2']);
//    }
}