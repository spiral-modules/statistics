<?php

namespace Spiral\Tests\Statistics\Extract\Intervals;

use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Extract;

class DailyTest extends AbstractInterval
{
    const RANGE          = Extract\Range::DAILY;
    const RANGE_FIELD    = 'day_mark';
    const RANGE_INTERVAL = 'P1D';
    const RANGE_FORMAT   = 'M, d Y';

    protected function start(): \DateTime
    {
        return new \DateTime('today noon - 2 days');
    }

    protected function end(): \DateTime
    {
        return new \DateTime('today noon + 2 days');
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
        return new \DateTime('today noon + 26 hours');
    }
}