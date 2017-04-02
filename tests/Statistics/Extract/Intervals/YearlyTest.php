<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class Yearly extends AbstractInterval
{
    const RANGE          = Extract\Range::YEARLY;
    const RANGE_FIELD    = 'year_mark';
    const RANGE_INTERVAL = 'P1Y';
    const RANGE_FORMAT   = 'Y';

    protected function start(): \DateTime
    {
        return new \DateTime('today noon - 370 days');
    }

    protected function end(): \DateTime
    {
        return new \DateTime('today noon + 370 days');
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
        return new \DateTime('today noon + 367 days + 2 hours');
    }

//    public function testYearly()
//    {
//        $rangeValue = Extract\Range::YEARLY;
//        /** @var Extract $extract */
//        $extract = $this->container->get(Extract::class);
//        /** @var Track $track */
//        $track = $this->container->get(Track::class);
//
//        $datetime = new \DateTime('this Monday of January');
//        $datetime2 = (new \DateTime('this Monday of January'))->add(new \DateInterval('P3M'));
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
//        $end = (new \DateTime('this Monday noon'))->add(new \DateInterval('P2Y'));
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
//        $start = (new \DateTime('this Monday noon'))->sub(new \DateInterval('P2Y'));
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