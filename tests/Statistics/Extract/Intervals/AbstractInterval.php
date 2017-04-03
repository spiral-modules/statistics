<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\ORM\Entities\RecordSelector;
use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Database\Sources\OccurrenceSource;
use Spiral\Statistics\DatetimeConverter;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Extract\Range;
use Spiral\Statistics\Track;
use Spiral\Tests\BaseTest;

abstract class AbstractInterval extends BaseTest
{
    const RANGE          = null;
    const RANGE_FIELD    = null;
    const RANGE_INTERVAL = null;
    const RANGE_FORMAT   = null;

    /** @var OccurrenceSource */
    protected $source;

    public function testRange()
    {
        $range = new Range(static::RANGE);
        $this->assertEquals(static::RANGE_FIELD, $range->getField());
        $this->assertEquals(new \DateInterval(static::RANGE_INTERVAL), $range->getInterval());
        $this->assertEquals(static::RANGE_FORMAT, $range->getFormat());
    }

    abstract protected function start(): \DateTime;

    abstract protected function end(): \DateTime;

    abstract protected function datetime1(): \DateTime;

    abstract protected function datetime2(): \DateTime;

    abstract protected function datetime3(): \DateTime;

    /**
     * Method shortcut.
     *
     * @param string             $periodField
     * @param \DateTimeInterface $periodValue
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param array              $events
     * @return RecordSelector
     */
    protected function find(
        string $periodField,
        \DateTimeInterface $periodValue,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        array $events = []
    ): RecordSelector
    {
        if (empty($this->source)) {
            $this->source =  $this->getSource();
        }

        return $this->source->findByGroupedInterval(
            $periodField,
            $periodValue,
            $start,
            $end,
            $events
        );
    }

    public function testSamePeriodOccurrenceSourceFindByGroupedInterval()
    {
        $converter = $this->getConverter();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $start = $this->start();
        $end = $this->end();

        $datetime1 = $this->datetime1();
        $datetime2 = $this->datetime2();
        $period = $converter->convert($datetime1, static::RANGE);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));
        $this->assertCount(0, $this->find(static::RANGE_FIELD, $period, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 3, 'event 4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(5, $this->orm->source(Event::class));

        //Check if passed events make difference
        $this->dataTest($period, $start, $end, [
            [2, []],
            [1, ['event 1']],
            [1, ['event 1', 'event 2']],
            [2, ['event 1', 'event 3']],
            [2, ['event 1', 'event 2', 'event 3', 'event 4']],
            [1, ['event 4']],
            [0, ['event 5']],
        ]);

        //Check if passed time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        $this->dataTest($period, $start1, $end, [
            [1, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [1, ['event 4']],
            [0, ['event 5']],
        ]);

        //Check if passed end time and events make difference
        $this->dataTest($period, $start, $end1, [
            [1, []],
            [1, ['event 1']],
            [1, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
    }

    public function testAnotherPeriodOccurrenceSourceFindByGroupedInterval()
    {
        $converter = $this->getConverter();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));

        $start = $this->start();
        $end = $this->end();

        $datetime1 = $this->datetime1();
        $datetime2 = $this->datetime3();
        $period1 = $converter->convert($datetime1, static::RANGE);
        $period2 = $converter->convert($datetime2, static::RANGE);

        $this->assertCount(0, $this->orm->source(Occurrence::class));
        $this->assertCount(0, $this->orm->source(Event::class));
        $this->assertCount(0, $this->find(static::RANGE_FIELD, $period1, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 3, 'event 4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(5, $this->orm->source(Event::class));

        //Check if passed events make difference
        $this->dataTest($period1, $start, $end, [
            [1, []],
            [1, ['event 1']],
            [1, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
        $this->dataTest($period2, $start, $end, [
            [1, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [1, ['event 4']],
            [0, ['event 5']],
        ]);

        //Check if passed start time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        $this->dataTest($period1, $start1, $end, [
            [0, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [0, ['event 1', 'event 3']],
            [0, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
        $this->dataTest($period2, $start1, $end, [
            [1, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [1, ['event 4']],
            [0, ['event 5']],
        ]);

        //Check if passed end time and events make difference
        $this->dataTest($period1, $start, $end1, [
            [1, []],
            [1, ['event 1']],
            [1, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
        $this->dataTest($period2, $start, $end1, [
            [0, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [0, ['event 1', 'event 3']],
            [0, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
    }

    protected function dataTest(
        \DateTimeInterface $period,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        array $data
    ) {
        $field = static::RANGE_FIELD;

        foreach ($data as $arr) {
            $this->assertCount($arr[0], $this->find($field, $period, $start, $end, $arr[1]));
        }
    }

//    public function testExtractEvents(){}

//    public function testExtract()
//    {
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
//        $range = new Extract\Range(static::RANGE);
//        $results = $extract->events(clone $start, clone $end, static::RANGE, ['event1', 'event2']);
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
//        $range = new Extract\Range(static::RANGE);
//        $results = $extract->events(clone $start, clone $end, static::RANGE, ['event1', 'event2']);
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
//        $range = new Extract\Range(static::RANGE);
//        $results = $extract->events(clone $start, clone $end, static::RANGE, ['event1', 'event2']);
//
//        $label = $end->format($range->getFormat());
//        $this->assertArrayHasKey($label, $results->results());
//        $this->assertArrayHasKey('event1', $results->results()[$label]);
//        $this->assertArrayHasKey('event2', $results->results()[$label]);
//        $this->assertEquals(4, $results->results()[$label]['event1']);
//        $this->assertEquals(6, $results->results()[$label]['event2']);
//    }
}