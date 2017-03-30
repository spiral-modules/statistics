<?php

namespace Spiral\Statistics\Exceptions;

use Spiral\Statistics\Extract;

class InvalidExtractDatasetException extends InvalidExtractException
{
    public function __construct($dataset)
    {
        parent::__construct(sprintf(
            'Invalid Dataset, should implement "%s", passed "%s"',
            Extract\DatasetInterface::class,
            $dataset
        ));
    }
}