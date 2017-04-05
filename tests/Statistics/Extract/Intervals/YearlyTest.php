<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class YearlyTest extends AbstractInterval
{
    const RANGE          = Extract\Range::YEARLY;
    const RANGE_FIELD    = 'year_mark';
    const RANGE_INTERVAL = 'P1Y';
    const RANGE_FORMAT   = 'Y';

    protected function start(): \DateTime
    {
        return new \DateTime('today noon - 370 days');
    }

    protected function end(): \DateTime
    {
        return new \DateTime('today noon + 370 days');
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
        return new \DateTime('today noon + 367 days + 2 hours');
    }
}