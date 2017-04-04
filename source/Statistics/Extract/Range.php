<?php

namespace Spiral\Statistics\Extract;

use Spiral\Statistics\Exceptions\InvalidExtractRangeException;
use Spiral\Statistics\Extract;

class Range
{
    const DAILY   = 'day';
    const WEEKLY  = 'week';
    const MONTHLY = 'month';
    const YEARLY  = 'year';

    const RANGES = [self::DAILY, self::WEEKLY, self::MONTHLY, self::YEARLY];

    /** @var string */
    private $range;

    /** @var \DateInterval */
    private $interval;

    /** @var string */
    private $format;

    /** @var string */
    private $field;

    /**
     * RangeState constructor.
     *
     * @param string $range
     */
    public function __construct(string $range)
    {
        if (!in_array($range, self::RANGES)) {
            throw new InvalidExtractRangeException($range);
        }

        $this->range = $range;
        $this->define();
    }

    /**
     * Define range values.
     */
    protected function define()
    {
        $interval = null;
        $format = null;
        $field = null;

        switch ($this->range) {
            case self::DAILY:
                $interval = 'P1D';
                $format = 'M, d Y';
                $field = 'day_mark';
                break;

            case self::WEEKLY:
                $interval = 'P7D';
                $format = 'W, Y';
                $field = 'week_mark';
                break;

            case self::MONTHLY:
                $interval = 'P1M';
                $format = 'M, Y';
                $field = 'month_mark';
                break;

            case self::YEARLY:
                $interval = 'P1Y';
                $format = 'Y';
                $field = 'year_mark';
                break;
        }

        $this->interval = new \DateInterval($interval);
        $this->format = $format;
        $this->field = $field;
    }

    /**
     * @return \DateInterval
     */
    public function getInterval(): \DateInterval
    {
        return $this->interval;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getRange(): string
    {
        return $this->range;
    }
}