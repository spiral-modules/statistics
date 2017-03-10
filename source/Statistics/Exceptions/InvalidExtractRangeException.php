<?php

namespace Spiral\Statistics\Exceptions;

use Spiral\Statistics\Extract;

class InvalidExtractRangeException extends InvalidExtractException
{
    public function __construct($range)
    {
        parent::__construct(sprintf(
            'Unsupported range "%s". Use %s class constants.',
            $range,
            Extract::class
        ));
    }
}