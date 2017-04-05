<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\ORM\Entities\RecordSelector;
use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Extract\Range;
use Spiral\Tests\BaseTest;

abstract class AbstractInterval extends BaseTest
{
    const RANGE          = null;
    const RANGE_FIELD    = null;
    const RANGE_INTERVAL = null;
    const RANGE_FORMAT   = null;

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
        return $this->getSource()->findByGroupedInterval(
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
        $this->sourceTest($period, $start, $end, [
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

        $this->sourceTest($period, $start1, $end, [
            [1, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [1, ['event 4']],
            [0, ['event 5']],
        ]);

        //Check if passed end time and events make difference
        $this->sourceTest($period, $start, $end1, [
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
        $this->assertCount(0, $this->find(static::RANGE_FIELD, $period2, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 3, 'event 4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(5, $this->orm->source(Event::class));

        //Check if passed events make difference
        $this->sourceTest($period1, $start, $end, [
            [1, []],
            [1, ['event 1']],
            [1, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
        $this->sourceTest($period2, $start, $end, [
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

        $this->sourceTest($period1, $start1, $end, [
            [0, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [0, ['event 1', 'event 3']],
            [0, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
        $this->sourceTest($period2, $start1, $end, [
            [1, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [1, ['event 4']],
            [0, ['event 5']],
        ]);

        //Check if passed end time and events make difference
        $this->sourceTest($period1, $start, $end1, [
            [1, []],
            [1, ['event 1']],
            [1, ['event 1', 'event 2']],
            [1, ['event 1', 'event 3']],
            [1, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
        $this->sourceTest($period2, $start, $end1, [
            [0, []],
            [0, ['event 1']],
            [0, ['event 1', 'event 2']],
            [0, ['event 1', 'event 3']],
            [0, ['event 1', 'event 2', 'event 3', 'event 4']],
            [0, ['event 4']],
            [0, ['event 5']],
        ]);
    }

    protected function sourceTest(
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

    public function testSamePeriodExtractEvents()
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

        $this->extractTest($period, $start, $end, [
            ['event 1' => 1],
            ['event 1' => 1, 'event 2' => 2],
            ['event 1' => 1, 'event 3' => 6],
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 6, 'event 4' => 4],
            ['event 4' => 4],
            ['event 5' => 0],
        ]);

        //Check if passed time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        $this->extractTest($period, $start1, $end, [
            ['event 1' => 0],
            ['event 1' => 0, 'event 2' => 0],
            ['event 1' => 0, 'event 3' => 3],
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 3, 'event 4' => 4],
            ['event 4' => 4],
            ['event 5' => 0],
        ]);

        $this->extractTest($period, $start, $end1, [
            ['event 1' => 1],
            ['event 1' => 1, 'event 2' => 2],
            ['event 1' => 1, 'event 3' => 3],
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 3, 'event 4' => 0],
            ['event 4' => 0],
            ['event 5' => 0],
        ]);
    }

    public function testAnotherPeriodExtractEvents()
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
        $this->assertCount(0, $this->find(static::RANGE_FIELD, $period2, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 3, 'event 4' => 4], $datetime2);

        $this->assertCount(2, $this->orm->source(Occurrence::class));
        $this->assertCount(5, $this->orm->source(Event::class));

        $this->extractTest($period1, $start, $end, [
            ['event 1' => 1],
            ['event 1' => 1, 'event 2' => 2],
            ['event 1' => 1, 'event 3' => 3],
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 3, 'event 4' => 0],
            ['event 4' => 0],
            ['event 5' => 0],
        ]);
        $this->extractTest($period2, $start, $end, [
            ['event 1' => 0],
            ['event 1' => 0, 'event 2' => 0],
            ['event 1' => 0, 'event 3' => 3],
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 3, 'event 4' => 4],
            ['event 4' => 4],
            ['event 5' => 0],
        ]);

        //Check if passed time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        $this->extractTest($period1, $start1, $end, [
            ['event 1' => 0],
            ['event 1' => 0, 'event 2' => 0],
            ['event 1' => 0, 'event 3' => 0],
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 0, 'event 4' => 0],
            ['event 4' => 0],
            ['event 5' => 0],
        ]);
        $this->extractTest($period2, $start1, $end, [
            ['event 1' => 0],
            ['event 1' => 0, 'event 2' => 0],
            ['event 1' => 0, 'event 3' => 3],
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 3, 'event 4' => 4],
            ['event 4' => 4],
            ['event 5' => 0],
        ]);

        $this->extractTest($period1, $start, $end1, [
            ['event 1' => 1],
            ['event 1' => 1, 'event 2' => 2],
            ['event 1' => 1, 'event 3' => 3],
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 3, 'event 4' => 0],
            ['event 4' => 0],
            ['event 5' => 0],
        ]);
        $this->extractTest($period2, $start, $end1, [
            ['event 1' => 0],
            ['event 1' => 0, 'event 2' => 0],
            ['event 1' => 0, 'event 3' => 0],
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 0, 'event 4' => 0],
            ['event 4' => 0],
            ['event 5' => 0],
        ]);
    }

    protected function extractTest(
        \DateTimeInterface $period,
        \DateTimeInterface $start,
        \DateTimeInterface $end,

        array $data
    ) {
        $extract = $this->getExtract();
        $count = $this->calculateRows($start, $end);

        foreach ($data as $arr) {
            $events = $extract->events($start, $end, static::RANGE, array_keys($arr));

            $rows = $events->getRows();
            //Same count of rows as expected due to grouping
            $this->assertCount($count, $rows);

            /** @var Extract\Events\Row $row */
            foreach ($rows as $label => $row) {
                //Same events with same values in result rows
                if ($period->format(static::RANGE_FORMAT) == $row->getLabel()) {
                    $this->assertEquals($arr, $row->getEvents());
                }
            }
        }
    }

    protected function calculateRows(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $converter = $this->getConverter();
        $start = $converter->immutable($start);
        $end = $converter->immutable($end);

        $count = 0;
        while ($start <= $end) {
            $count++;
            $start = $start->add(new \DateInterval(static::RANGE_INTERVAL));
        }

        return $count;
    }
}