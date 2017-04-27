<?php

namespace Spiral\Statistics\Extract\Range;

class MonthlyRange extends AbstractRange
{
    const RANGE    = self::MONTHLY;
    const INTERVAL = 'P1M';
    const FORMAT   = 'M, Y';
}