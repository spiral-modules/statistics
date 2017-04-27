<?php

namespace Spiral\Statistics\Extract\Range;

use Spiral\Statistics\Exceptions\InvalidExtractRangeException;
use Spiral\Statistics\Extract\RangeInterface;

class Factory
{
    /**
     * @param string $range
     * @return RangeInterface
     */
    public static function getRange(string $range): RangeInterface
    {
        if (!in_array($range, RangeInterface::RANGES)) {
            throw new InvalidExtractRangeException($range);
        }

        $className = 'Spiral\\Statistics\\Extract\\Range\\' . ucfirst($range) . 'Range';

        return new $className;
    }
}