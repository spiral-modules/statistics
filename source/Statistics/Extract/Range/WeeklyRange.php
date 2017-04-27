<?php

namespace Spiral\Statistics\Extract\Range;

class WeeklyRange extends AbstractRange
{
    const RANGE    = self::WEEKLY;
    const INTERVAL = 'P7D';
    const FORMAT   = 'W, Y';
}