<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\DatasetInterface;
use Spiral\Statistics\Extract\Events;

abstract class AbstractDataset implements DatasetInterface
{
    /** @var array|Events\Row[] */
    protected $raw = [];

    /** @var array */
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function setData(Events $events)
    {
        $this->raw = $events->getRows();
        $this->convert();
    }

    /**
     * Convert chart data if required.
     */
    abstract protected function convert();
}