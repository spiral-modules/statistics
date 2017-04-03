<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class DailyTest extends AbstractInterval
{
    const RANGE          = Extract\Range::DAILY;
    const RANGE_FIELD    = 'day_mark';
    const RANGE_INTERVAL = 'P1D';
    const RANGE_FORMAT   = 'M, d Y';

//    public function testDailyExtract()
//    {
//        $rangeValue = Extract\Range::DAILY;
//        /** @var Extract $extract */
//        $extract = $this->container->get(Extract::class);
//        /** @var Track $track */
//        $track = $this->container->get(Track::class);
//
//        $datetime = new \DateTime('today noon');
//        $datetime2 = new \DateTime('today noon + 2 hours');
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
//        $start = new \DateTime('today');
//        $end = new \DateTime('today + 7 days');
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
//        $start = new \DateTime('today - 7 days');
//        $end = new \DateTime('today');
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
//        $start = $end = new \DateTime('today');
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

    protected function start(): \DateTime
    {
        return new \DateTime('today noon - 2 days');
    }

    protected function end(): \DateTime
    {
        return new \DateTime('today noon + 2 days');
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
        return new \DateTime('today noon + 26 hours');
    }
}