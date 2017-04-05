<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Extract;

class WeeklyTest extends AbstractInterval
{
    const RANGE          = Extract\Range::WEEKLY;
    const RANGE_FIELD    = 'week_mark';
    const RANGE_INTERVAL = 'P7D';
    const RANGE_FORMAT   = 'W, Y';

    protected function start(): \DateTime
    {
        return new \DateTime('today noon - 22 days');
    }

    protected function end(): \DateTime
    {
        return new \DateTime('today noon + 22 days');
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
        return new \DateTime('today noon + 8 days + 2 hours');
    }
}