<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Database\Statistics;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Extract\Range;
use Spiral\Tests\BaseTest;

abstract class AbstractInterval extends BaseTest
{
    const RANGE_INTERVAL = null;
    const RANGE_FORMAT   = null;

    const START = null; //extract start date
    const END   = null; //extract end date

    const DT1 = null; //track first datetime
    const DT2 = null; //track second datetime in same interval
    const DT3 = null; //track second datetime in another interval

    public function testRange()
    {
        $range = $this->range();
        $this->assertEquals(new \DateInterval(static::RANGE_INTERVAL), $range->getInterval());
        $this->assertEquals(static::RANGE_FORMAT, $range->getFormat());
    }

    /**
     * Method shortcut.
     *
     * @param \DateTimeInterface $periodValue
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param array              $events
     * @return array
     */
    protected function find(
        \DateTimeInterface $periodValue,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        array $events = []
    ): array
    {
        $result = [];
        /** @var Statistics $row */
        foreach ($this->getSource()->findExtract($start, $end, $events) as $row) {
            $converted = $this->getConverter()->convert($row->timestamp, $this->range());
            if ($converted->getTimestamp() == $periodValue->getTimestamp()) {
                $result[] = $row;
            }
        }

        return $result;
    }

    public function testSamePeriodSourceFindExtract()
    {
        $converter = $this->getConverter();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $start = $this->start();
        $end = $this->end();

        $datetime1 = $this->datetime1();
        $datetime2 = $this->datetime2();
        $period = $converter->convert($datetime1, $this->range());

        $this->assertCount(0, $this->orm->source(Statistics::class));
        $this->assertCount(0, $this->find($period, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 3, 'event 4' => 4], $datetime2);

        $this->assertCount(5, $this->orm->source(Statistics::class));

        /*
         * Check if passed events make difference
         * Start-end cover all occurrences
         */
        $this->assertCount(5, $this->find($period, $start, $end, []));
        $this->assertCount(1, $this->find($period, $start, $end, ['event 1']));
        $this->assertCount(2, $this->find($period, $start, $end, ['event 1', 'event 2']));
        $this->assertCount(3, $this->find($period, $start, $end, ['event 1', 'event 3']));
        $this->assertCount(5,
            $this->find($period, $start, $end, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(1, $this->find($period, $start, $end, ['event 4']));
        $this->assertCount(0, $this->find($period, $start, $end, ['event 5']));

        //Check if passed time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        /*
         * Check if passed start time and events make difference
         * Start-end cover second occurrences
         */
        $this->assertCount(2, $this->find($period, $start1, $end, []));
        $this->assertCount(0, $this->find($period, $start1, $end, ['event 1']));
        $this->assertCount(0, $this->find($period, $start1, $end, ['event 1', 'event 2']));
        $this->assertCount(1, $this->find($period, $start1, $end, ['event 1', 'event 3']));
        $this->assertCount(2,
            $this->find($period, $start1, $end, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(1, $this->find($period, $start1, $end, ['event 4']));
        $this->assertCount(0, $this->find($period, $start1, $end, ['event 5']));

        /*
         * Check if passed end time and events make difference
         * Start-end cover first occurrences
         */
        $this->assertCount(3, $this->find($period, $start, $end1, []));
        $this->assertCount(1, $this->find($period, $start, $end1, ['event 1']));
        $this->assertCount(2, $this->find($period, $start, $end1, ['event 1', 'event 2']));
        $this->assertCount(2, $this->find($period, $start, $end1, ['event 1', 'event 3']));
        $this->assertCount(3,
            $this->find($period, $start, $end1, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(0, $this->find($period, $start, $end1, ['event 4']));
        $this->assertCount(0, $this->find($period, $start, $end1, ['event 5']));
    }

    public function testAnotherPeriodSourceFindExtract()
    {
        $converter = $this->getConverter();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $start = $this->start();
        $end = $this->end();

        $datetime1 = $this->datetime1();
        $datetime2 = $this->datetime3();
        $period1 = $converter->convert($datetime1, $this->range());
        $period2 = $converter->convert($datetime2, $this->range());

        $this->assertCount(0, $this->orm->source(Statistics::class));
        $this->assertCount(0, $this->find($period1, $start, $end));
        $this->assertCount(0, $this->find($period2, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 3, 'event 4' => 4], $datetime2);

        $this->assertCount(5, $this->orm->source(Statistics::class));

        /*
         * Check if passed events make difference
         * Start-end cover all occurrences
         *
         * Only first occurrences
         */
        $this->assertCount(3, $this->find($period1, $start, $end, []));
        $this->assertCount(1, $this->find($period1, $start, $end, ['event 1']));
        $this->assertCount(2, $this->find($period1, $start, $end, ['event 1', 'event 2']));
        $this->assertCount(2, $this->find($period1, $start, $end, ['event 1', 'event 3']));
        $this->assertCount(3,
            $this->find($period1, $start, $end, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(0, $this->find($period1, $start, $end, ['event 4']));
        $this->assertCount(0, $this->find($period1, $start, $end, ['event 5']));

        /*
         * Only second occurrences
         */
        $this->assertCount(2, $this->find($period2, $start, $end, []));
        $this->assertCount(0, $this->find($period2, $start, $end, ['event 1']));
        $this->assertCount(0, $this->find($period2, $start, $end, ['event 1', 'event 2']));
        $this->assertCount(1, $this->find($period2, $start, $end, ['event 1', 'event 3']));
        $this->assertCount(2,
            $this->find($period2, $start, $end, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(1, $this->find($period2, $start, $end, ['event 4']));
        $this->assertCount(0, $this->find($period2, $start, $end, ['event 5']));

        //Check if passed start time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        /*
         * Check if passed start time and events and period make difference
         * Start-end cover second occurrences
         */
        $this->assertCount(0, $this->find($period1, $start1, $end, []));
        $this->assertCount(0, $this->find($period1, $start1, $end, ['event 1']));
        $this->assertCount(0, $this->find($period1, $start1, $end, ['event 1', 'event 2']));
        $this->assertCount(0, $this->find($period1, $start1, $end, ['event 1', 'event 3']));
        $this->assertCount(0,
            $this->find($period1, $start1, $end, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(0, $this->find($period1, $start1, $end, ['event 4']));
        $this->assertCount(0, $this->find($period1, $start1, $end, ['event 5']));

        $this->assertCount(2, $this->find($period2, $start1, $end, []));
        $this->assertCount(0, $this->find($period2, $start1, $end, ['event 1']));
        $this->assertCount(0, $this->find($period2, $start1, $end, ['event 1', 'event 2']));
        $this->assertCount(1, $this->find($period2, $start1, $end, ['event 1', 'event 3']));
        $this->assertCount(2,
            $this->find($period2, $start1, $end, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(1, $this->find($period2, $start1, $end, ['event 4']));
        $this->assertCount(0, $this->find($period2, $start1, $end, ['event 5']));

        /*
         * Check if passed end time and events make difference
         * Start-end cover first occurrences
         */
        $this->assertCount(3, $this->find($period1, $start, $end1, []));
        $this->assertCount(1, $this->find($period1, $start, $end1, ['event 1']));
        $this->assertCount(2, $this->find($period1, $start, $end1, ['event 1', 'event 2']));
        $this->assertCount(2, $this->find($period1, $start, $end1, ['event 1', 'event 3']));
        $this->assertCount(3,
            $this->find($period1, $start, $end1, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(0, $this->find($period1, $start, $end1, ['event 4']));
        $this->assertCount(0, $this->find($period1, $start, $end1, ['event 5']));

        $this->assertCount(0, $this->find($period2, $start, $end1, []));
        $this->assertCount(0, $this->find($period2, $start, $end1, ['event 1']));
        $this->assertCount(0, $this->find($period2, $start, $end1, ['event 1', 'event 2']));
        $this->assertCount(0, $this->find($period2, $start, $end1, ['event 1', 'event 3']));
        $this->assertCount(0,
            $this->find($period2, $start, $end1, ['event 1', 'event 2', 'event 3', 'event 4']));
        $this->assertCount(0, $this->find($period2, $start, $end1, ['event 4']));
        $this->assertCount(0, $this->find($period2, $start, $end1, ['event 5']));
    }

    public function testSamePeriodExtractEvents()
    {
        $converter = $this->getConverter();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $start = $this->start();
        $end = $this->end();

        $datetime1 = $this->datetime1();
        $datetime2 = $this->datetime2();
        $period = $converter->convert($datetime1, $this->range());

        $this->assertCount(0, $this->orm->source(Statistics::class));
        $this->assertCount(0, $this->find($period, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 4, 'event 4' => 4], $datetime2);

        $this->assertCount(5, $this->orm->source(Statistics::class));

        //Check if passed time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        $this->extractTest($period, $start, $end,
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 7, 'event 4' => 4, 'event 5' => 0]);

        $this->extractTest($period, $start1, $end,
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 4, 'event 4' => 4, 'event 5' => 0]);

        $this->extractTest($period, $start, $end1,
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 3, 'event 4' => 0, 'event 5' => 0]);
    }

    public function testAnotherPeriodExtractEvents()
    {
        $converter = $this->getConverter();
        $track = $this->getTrack();

        $this->assertCount(0, $this->orm->source(Statistics::class));

        $start = $this->start();
        $end = $this->end();

        $datetime1 = $this->datetime1();
        $datetime2 = $this->datetime3();
        $period1 = $converter->convert($datetime1, $this->range());
        $period2 = $converter->convert($datetime2, $this->range());

        $this->assertCount(0, $this->orm->source(Statistics::class));
        $this->assertCount(0, $this->find($period1, $start, $end));
        $this->assertCount(0, $this->find($period2, $start, $end));

        $track->events(['event 1' => 1, 'event 2' => 2, 'event 3' => 3], $datetime1);
        $track->events(['event 3' => 4, 'event 4' => 4], $datetime2);

        $this->assertCount(5, $this->orm->source(Statistics::class));

        //Check if passed time and events make difference
        $start1 = clone $datetime1;
        $start1->setTime(13, 0, 0);

        $end1 = clone $datetime1;
        $end1->setTime(13, 0, 0);

        $this->extractTest($period1, $start, $end,
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 3, 'event 4' => 0, 'event 5' => 0]);

        $this->extractTest($period1, $start1, $end,
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 0, 'event 4' => 0, 'event 5' => 0]);

        $this->extractTest($period1, $start, $end1,
            ['event 1' => 1, 'event 2' => 2, 'event 3' => 3, 'event 4' => 0, 'event 5' => 0]);

        $this->extractTest($period2, $start, $end,
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 4, 'event 4' => 4, 'event 5' => 0]);

        $this->extractTest($period2, $start1, $end,
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 4, 'event 4' => 4, 'event 5' => 0]);

        $this->extractTest($period2, $start, $end1,
            ['event 1' => 0, 'event 2' => 0, 'event 3' => 0, 'event 4' => 0, 'event 5' => 0]);
    }

    protected function extractTest(
        \DateTimeInterface $period,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        array $arr
    ) {
        $extract = $this->getExtract();
        $count = $this->calculateRows($start, $end);

        $events = $extract->events($start, $end, $this->range(), array_keys($arr));

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

    protected function calculateRows(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $converter = $this->getConverter();
        $start = $converter->immutable($start);
        $end = $converter->immutable($end);

        $count = 0;
        while (
            $this->getConverter()->convert($start, $this->range()) <=
            $this->getConverter()->convert($end, $this->range())
        ) {
            $count++;
            $start = $start->add(new \DateInterval(static::RANGE_INTERVAL));
        }

        return $count;
    }

    abstract protected function range(): Extract\RangeInterface;

    protected function start(): \DateTime
    {
        return new \DateTime(static::START);
    }

    protected function end(): \DateTime
    {
        return new \DateTime(static::END);
    }

    protected function datetime1(): \DateTime
    {
        return new \DateTime(static::DT1);
    }

    protected function datetime2(): \DateTime
    {
        return new \DateTime(static::DT2);
    }

    protected function datetime3(): \DateTime
    {
        return new \DateTime(static::DT3);
    }
}