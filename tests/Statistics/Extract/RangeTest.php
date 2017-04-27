<?php

namespace Spiral\Tests\Statistics\Extract;

use Spiral\Statistics\Extract\Range;
use Spiral\Statistics\Extract\RangeInterface;
use Spiral\Tests\BaseTest;

class RangeTest extends BaseTest
{
    /**
     * Test unsupported range interval
     *
     * @expectedException \Spiral\Statistics\Exceptions\InvalidExtractRangeException
     */
    public function testFactoryUnknownRange()
    {
        $factory = new Range\Factory();
        $factory->getRange('some-range');
    }

    /**
     * Test unsupported range interval
     */
    public function testFactoryKnownRange()
    {
        $factory = new Range\Factory();
        $range = $factory->getRange(RangeInterface::DAILY);

        $this->assertInstanceOf(Range\DailyRange::class, $range);
    }

    /**
     * Test supported range interval
     */
    public function testSupportedRange()
    {
        $range = new Range\DailyRange();
        $this->assertEquals($range->getRange(), RangeInterface::DAILY);
        $this->assertNotEmpty($range->getFormat());
        $this->assertNotEmpty($range->getInterval());
    }
}