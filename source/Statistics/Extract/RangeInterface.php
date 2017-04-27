<?php

namespace Spiral\Statistics\Extract;

interface RangeInterface
{
    const DEFAULT  = 'undefined';

    const INTERVAL = self::DEFAULT;
    const FORMAT   = self::DEFAULT;

    const RANGE    = self::DEFAULT;
    const DAILY   = 'daily';
    const WEEKLY  = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY  = 'yearly';

    const RANGES = [self::DAILY, self::WEEKLY, self::MONTHLY, self::YEARLY];

    /**
     * @return \DateInterval
     */
    public function getInterval(): \DateInterval;

    /**
     * @return string
     */
    public function getFormat(): string;

    /**
     * @return string
     */
    public function getRange(): string;
}