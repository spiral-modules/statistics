<?php

namespace Spiral\Statistics\Extract\Range;

class DailyRange extends AbstractRange
{
    const RANGE    = self::DAILY;
    const INTERVAL = 'P1D';
    const FORMAT   = 'M, d Y';
}