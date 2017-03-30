<?php

namespace Spiral\Statistics\Extract;

use Spiral\Statistics\Exceptions\InvalidExtractDatasetException;
use Spiral\Statistics\Extract\Events\Row;

class Events
{
    /** @var array */
    protected $events = [];

    /** @var array */
    protected $rows = [];

    /**
     * Results constructor.
     *
     * @param array $events
     */
    public function __construct(array $events)
    {
        $this->events = $events;
    }

    /**
     * @param string $label
     * @return Row
     */
    public function addRow(string $label): Row
    {
        $row = new Row($label, $this->events);
        $this->rows[$label] = $row;

        return $row;
    }

    /**
     * @param string $dataset
     * @param array  $labels
     * @return DatasetInterface
     */
    public function getDataset(string $dataset, array $labels):DatasetInterface
    {
        if (!$this->isValidDataset($dataset)) {
            throw new InvalidExtractDatasetException($dataset);
        }

        return new $dataset($this->rows, $labels);
    }

    /**
     * @param string $dataset
     * @return bool
     */
    protected function isValidDataset(string $dataset): bool
    {
        $interfaces = class_implements($dataset);

        return $interfaces && in_array(DatasetInterface::class, $interfaces);
    }
}