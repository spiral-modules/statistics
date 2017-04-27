<?php

namespace Spiral\Statistics\Extract\Range;

class YearlyRange extends AbstractRange
{
    const RANGE    = self::YEARLY;
    const INTERVAL = 'P1Y';
    const FORMAT   = 'Y';
}