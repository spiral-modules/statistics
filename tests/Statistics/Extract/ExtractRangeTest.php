<?php

namespace Spiral\Tests\Statistics\Extract;


use Spiral\Statistics\Exceptions\InvalidExtractRangeException;
use Spiral\Statistics\Extract;
use Spiral\Statistics\Extract\ExtractRange;
use Spiral\Tests\BaseTest;

class ExtractRangeTest extends BaseTest
{
    public function testUnsupportedRange()
    {
        $this->expectException(InvalidExtractRangeException::class);
        $range = new ExtractRange('abc');
        $this->assertEquals($range->getRange(), 'abc');
    }

    public function testSupportedRange()
    {
        $range = new ExtractRange(Extract::DAILY);
        $this->assertEquals($range->getRange(), Extract::DAILY);
    }

    public function testDailyRange()
    {
        $range = new ExtractRange(Extract::DAILY);
        $this->assertEquals('day_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P1D'), $range->getInterval());
        $this->assertEquals('M, d Y', $range->getFormat());
    }

    public function testWeeklyRange()
    {
        $range = new ExtractRange(Extract::WEEKLY);
        $this->assertEquals('week_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P7D'), $range->getInterval());
        $this->assertEquals('W, Y', $range->getFormat());
    }

    public function testMonthlyRange()
    {
        $range = new ExtractRange(Extract::MONTHLY);
        $this->assertEquals('month_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P1M'), $range->getInterval());
        $this->assertEquals('M, Y', $range->getFormat());
    }

    public function testYearlyRange()
    {
        $range = new ExtractRange(Extract::YEARLY);
        $this->assertEquals('year_mark', $range->getField());
        $this->assertEquals(new \DateInterval('P1Y'), $range->getInterval());
        $this->assertEquals('Y', $range->getFormat());
    }
}