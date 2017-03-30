<?php

namespace Spiral\Tests\Statistics\Extract;


use Spiral\Statistics\Exceptions\InvalidExtractRangeException;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Extract\Range;
use Spiral\Tests\BaseTest;

class RangeTest extends BaseTest
{
    public function testUnsupportedRange()
    {
        $this->expectException(InvalidExtractRangeException::class);
        $range = new Range('abc');
        $this->assertEquals($range->getRange(), 'abc');
    }

    public function testSupportedRange()
    {
        $range = new Range(Extract::DAILY);
        $this->assertEquals($range->getRange(), Extract::DAILY);
    }

    public function testDailyRange()
    {
        $range = new Range(Extract::DAILY);
        $this->assertEquals('day_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P1D'), $range->getInterval());
        $this->assertEquals('M, d Y', $range->getFormat());
    }

    public function testWeeklyRange()
    {
        $range = new Range(Extract::WEEKLY);
        $this->assertEquals('week_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P7D'), $range->getInterval());
        $this->assertEquals('W, Y', $range->getFormat());
    }

    public function testMonthlyRange()
    {
        $range = new Range(Extract::MONTHLY);
        $this->assertEquals('month_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P1M'), $range->getInterval());
        $this->assertEquals('M, Y', $range->getFormat());
    }

    public function testYearlyRange()
    {
        $range = new Range(Extract::YEARLY);
        $this->assertEquals('year_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P1Y'), $range->getInterval());
        $this->assertEquals('Y', $range->getFormat());
    }
}