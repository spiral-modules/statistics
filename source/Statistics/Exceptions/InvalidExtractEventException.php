<?php

namespace Spiral\Statistics\Exceptions;

use Spiral\Statistics\Extract;

class InvalidExtractEventException extends InvalidExtractException
{
    public function __construct($event)
    {
        parent::__construct(sprintf(
            'Unknown event "%s", should be passed in construct.',
            $event
        ));
    }
}