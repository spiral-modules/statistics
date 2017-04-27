<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class DailyTest extends AbstractInterval
{
    const RANGE_INTERVAL = 'P1D';
    const RANGE_FORMAT   = 'M, d Y';

    const START = 'today noon - 2 days';
    const END   = 'today noon + 2 days';

    const DT1 = 'today noon';
    const DT2 = 'today noon + 2 hours';
    const DT3 = 'today noon + 26 hours';

    protected function range(): Extract\RangeInterface
    {
        return new Extract\Range\DailyRange();
    }
}