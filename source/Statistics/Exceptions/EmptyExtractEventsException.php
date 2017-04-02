<?php

namespace Spiral\Statistics\Exceptions;

use Spiral\Statistics\Extract;

class EmptyExtractEventsException extends InvalidExtractException
{
    public function __construct()
    {
        parent::__construct('Extract events are not defined, empty array passed.');
    }
}