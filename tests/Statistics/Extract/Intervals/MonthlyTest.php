<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class MonthlyTest extends AbstractInterval
{
    const RANGE          = Extract\Range::MONTHLY;
    const RANGE_FIELD    = 'month_mark';
    const RANGE_INTERVAL = 'P1M';
    const RANGE_FORMAT   = 'M, Y';

    protected function start(): \DateTime
    {
        return new \DateTime('today noon - 35 days');
    }

    protected function end(): \DateTime
    {
        return new \DateTime('today noon + 35 days');
    }

    protected function datetime1(): \DateTime
    {
        return new \DateTime('today noon');
    }

    protected function datetime2(): \DateTime
    {
        return new \DateTime('today noon + 2 hours');
    }

    protected function datetime3(): \DateTime
    {
        return new \DateTime('today noon + 33 days + 2 hours');
    }
}