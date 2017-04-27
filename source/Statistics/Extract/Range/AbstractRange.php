<?php

namespace Spiral\Statistics\Extract\Range;

use Spiral\Statistics\Extract\RangeInterface;

abstract class AbstractRange implements RangeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getInterval(): \DateInterval
    {
        return new \DateInterval(static::INTERVAL);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat(): string
    {
        return static::FORMAT;
    }

    /**
     * {@inheritdoc}
     */
    public function getRange(): string
    {
        return static::RANGE;
    }
}