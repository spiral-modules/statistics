<?php

namespace Spiral\Tests\Statistics;

use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\DatetimeConverter;
use Spiral\Statistics\Exceptions\InvalidExtractException;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Track;
use Spiral\Tests\BaseTest;

class ExtractTest extends BaseTest
{
    public function testEmptyEvents()
    {
        /** @var Extract $extract */
        $extract = $this->container->get(Extract::class);

        $this->expectException(InvalidExtractException::class);
        $extract->events(new \DateTime(), new \DateTime(), 'range', []);
    }

    public function testDateSwap()
    {
        /** @var Extract $extract */
        $extract = $this->container->get(Extract::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

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
            $extract->events(clone $start, clone  $end, Extract::DAILY, ['event1']),
            $extract->events(clone $end, clone $start, Extract::DAILY, ['event1'])
        );
    }

    public function testDaily()
    {
        $rangeValue = Extract::DAILY;
        /** @var Extract $extract */
        $extract = $this->container->get(Extract::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $datetime = new \DateTime('today noon');
        $datetime2 = new \DateTime('today noon + 2 hours');

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $track->events([
            'event1' => 1,
            'event2' => 2
        ], $datetime);

        $track->events([
            'event1' => 3,
            'event2' => 4
        ], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(4, $this->orm->source(Event::class));

        //test start same date
        $start = new \DateTime('today');
        $end = new \DateTime('today + 7 days');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $start->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test start end date
        $start = new \DateTime('today - 7 days');
        $end = new \DateTime('today');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test same start and end date
        $start = $end = new \DateTime('today');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);
    }

    public function testWeekly()
    {
        $rangeValue = Extract::WEEKLY;
        /** @var Extract $extract */
        $extract = $this->container->get(Extract::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $datetime = new \DateTime('this Monday noon');
        $datetime2 = (new \DateTime('this Monday noon'))->add(new \DateInterval('P1D'));

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $track->events([
            'event1' => 1,
            'event2' => 2
        ], $datetime);

        $track->events([
            'event1' => 3,
            'event2' => 4
        ], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(4, $this->orm->source(Event::class));

        //test start same date
        $start = new \DateTime('this Monday noon');
        $end = (new \DateTime('this Monday noon'))->add(new \DateInterval('P21D'));
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $start->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test start end date
        $start = (new \DateTime('this Monday noon'))->sub(new \DateInterval('P21D'));
        $end = new \DateTime('this Monday noon');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test same start and end date
        $start = $end = new \DateTime('this Monday noon');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);
    }

    public function testMonthly()
    {
        $rangeValue = Extract::MONTHLY;
        /** @var Extract $extract */
        $extract = $this->container->get(Extract::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $datetime = new \DateTime('this Monday noon');
        $datetime2 = (new \DateTime('this Monday noon'))->add(new \DateInterval('PT1H'));

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $track->events([
            'event1' => 1,
            'event2' => 2
        ], $datetime);

        $track->events([
            'event1' => 3,
            'event2' => 4
        ], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(4, $this->orm->source(Event::class));

        //test start same date
        $start = new \DateTime('this Monday noon');
        $end = (new \DateTime('this Monday noon'))->add(new \DateInterval('P4M'));
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $start->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test start end date
        $start = (new \DateTime('this Monday noon'))->sub(new \DateInterval('P4M'));
        $end = new \DateTime('this Monday noon');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test same start and end date
        $start = $end = new \DateTime('this Monday noon');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);
    }

    public function testYearly()
    {
        $rangeValue = Extract::YEARLY;
        /** @var Extract $extract */
        $extract = $this->container->get(Extract::class);
        /** @var Track $track */
        $track = $this->container->get(Track::class);

        $datetime = new \DateTime('this Monday of January');
        $datetime2 = (new \DateTime('this Monday of January'))->add(new \DateInterval('P3M'));

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $track->events([
            'event1' => 1,
            'event2' => 2
        ], $datetime);

        $track->events([
            'event1' => 3,
            'event2' => 4
        ], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(4, $this->orm->source(Event::class));

        //test start same date
        $start = new \DateTime('this Monday noon');
        $end = (new \DateTime('this Monday noon'))->add(new \DateInterval('P2Y'));
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $start->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test start end date
        $start = (new \DateTime('this Monday noon'))->sub(new \DateInterval('P2Y'));
        $end = new \DateTime('this Monday noon');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);

        //test same start and end date
        $start = $end = new \DateTime('this Monday noon');
        $range = new Extract\ExtractRange($rangeValue);
        $results = $extract->events(clone $start, clone $end, $rangeValue, ['event1', 'event2']);

        $label = $end->format($range->getFormat());
        $this->assertArrayHasKey($label, $results->results());
        $this->assertArrayHasKey('event1', $results->results()[$label]);
        $this->assertArrayHasKey('event2', $results->results()[$label]);
        $this->assertEquals(4, $results->results()[$label]['event1']);
        $this->assertEquals(6, $results->results()[$label]['event2']);
    }
}