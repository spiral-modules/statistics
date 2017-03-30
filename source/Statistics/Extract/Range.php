<?php

namespace Spiral\Statistics\Extract;

use Spiral\Statistics\Exceptions\InvalidExtractRangeException;
use Spiral\Statistics\Extract;

class Range
{
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
        if (!in_array($range, Extract::RANGES)) {
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
            case Extract::DAILY:
                $interval = 'P1D';
                break;

            case Extract::WEEKLY:
                $interval = 'P7D';
                break;

            case Extract::MONTHLY:
                $interval = 'P1M';
                break;

            case Extract::YEARLY:
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
            case Extract::DAILY:
                $format = 'M, d Y';
                break;

            case Extract::WEEKLY:
                $format = 'W, Y';
                break;

            case Extract::MONTHLY:
                $format = 'M, Y';
                break;

            case Extract::YEARLY:
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
            case Extract::DAILY:
                $field = 'day_mark';
                break;

            case Extract::WEEKLY:
                $field = 'week_mark';
                break;

            case Extract::MONTHLY:
                $field = 'month_mark';
                break;

            case Extract::YEARLY:
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