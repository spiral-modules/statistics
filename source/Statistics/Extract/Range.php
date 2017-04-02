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
        $this->interval = $this->calcInterval($range);
        $this->format = $this->defineDateFormat($range);
        $this->field = $this->defineSearchQueryField($range);
    }

    /**
     * @param string $range
     * @return \DateInterval
     */
    protected function calcInterval(string $range): \DateInterval
    {
        $interval = null;

        switch ($range) {
            case self::DAILY:
                $interval = 'P1D';
                break;

            case self::WEEKLY:
                $interval = 'P7D';
                break;

            case self::MONTHLY:
                $interval = 'P1M';
                break;

            case self::YEARLY:
                $interval = 'P1Y';
                break;
        }

        return new \DateInterval($interval);
    }

    /**
     * @param string $range
     * @return string
     */
    protected function defineDateFormat(string $range): string
    {
        $format = null;

        switch ($range) {
            case self::DAILY:
                $format = 'M, d Y';
                break;

            case self::WEEKLY:
                $format = 'W, Y';
                break;

            case self::MONTHLY:
                $format = 'M, Y';
                break;

            case self::YEARLY:
                $format = 'Y';
                break;
        }

        return $format;
    }

    /**
     * @param string $range
     * @return string
     */
    protected function defineSearchQueryField(string $range): string
    {
        $field = null;

        switch ($range) {
            case self::DAILY:
                $field = 'day_mark';
                break;

            case self::WEEKLY:
                $field = 'week_mark';
                break;

            case self::MONTHLY:
                $field = 'month_mark';
                break;

            case self::YEARLY:
                $field = 'year_mark';
                break;
        }

        return $field;
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