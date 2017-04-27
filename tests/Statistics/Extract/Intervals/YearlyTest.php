<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class YearlyTest extends AbstractInterval
{
    const RANGE_INTERVAL = 'P1Y';
    const RANGE_FORMAT   = 'Y';

    const START = 'today noon - 370 days';
    const END = 'today noon + 370 days';

    const DT1 = 'today noon';
    const DT2 = 'today noon + 2 hours';
    const DT3 = 'today noon + 367 days + 2 hours';

    protected function range(): Extract\RangeInterface
    {
        return new Extract\Range\YearlyRange();
    }
}