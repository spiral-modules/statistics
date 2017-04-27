<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class MonthlyTest extends AbstractInterval
{
    const RANGE_INTERVAL = 'P1M';
    const RANGE_FORMAT   = 'M, Y';

    const START = 'today noon - 35 days';
    const END = 'today noon + 35 days';

    const DT1 = 'today noon';
    const DT2 = 'today noon + 2 hours';
    const DT3 = 'today noon + 33 days + 2 hours';

    protected function range(): Extract\RangeInterface
    {
        return new Extract\Range\MonthlyRange();
    }
}