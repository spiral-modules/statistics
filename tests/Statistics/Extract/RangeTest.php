<?php

namespace Spiral\Tests\Statistics\Extract;

use Spiral\Statistics\Extract\Range;
use Spiral\Tests\BaseTest;

class RangeTest extends BaseTest
{
    /**
     * Test unsupported range interval
     *
     * @expectedException \Spiral\Statistics\Exceptions\InvalidExtractRangeException
     */
    public function testUnsupportedRange()
    {
        $range = new Range('abc');
        $this->assertEquals($range->getRange(), 'abc');
    }

    /**
     * Test supported range interval
     */
    public function testSupportedRange()
    {
        $input = Range::DAILY;
        $range = new Range($input);
        $this->assertEquals($range->getRange(), $input);
        $this->assertNotEmpty($range->getFormat());
        $this->assertNotEmpty($range->getField());
        $this->assertNotEmpty($range->getInterval());
    }
}