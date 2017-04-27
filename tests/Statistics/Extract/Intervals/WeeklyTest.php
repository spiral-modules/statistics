<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class WeeklyTest extends AbstractInterval
{
    const RANGE_INTERVAL = 'P7D';
    const RANGE_FORMAT   = 'W, Y';

    const START = 'today noon - 22 days';
    const END = 'today noon + 22 days';

    const DT1 = 'today noon';
    const DT2 = 'today noon + 2 hours';
    const DT3 = 'today noon + 8 days + 2 hours';

    protected function range(): Extract\RangeInterface
    {
        return new Extract\Range\WeeklyRange();
    }
}