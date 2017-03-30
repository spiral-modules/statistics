<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\DatasetInterface;

class GoogleChartDataset implements DatasetInterface
{
    /** @var array */
    private $columns;

    /** @var array */
    private $data;

    /**
     * Chart constructor.
     *
     * @param array $columns
     * @param array $data
     */
    public function __construct(array $columns, array $data)
    {
        $this->columns = $columns;
        $this->data = $this->convertData($data);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function convertData(array $data): array
    {
        $converted = [];
        foreach ($data as $label => $events) {
            $row = array_values($events);
            array_unshift($row, $label);

            $converted[] = $row;
        }

        return $converted;
    }

    /**
     * Get columns (names).
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Get data (values).
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getDataTable(): array
    {
        return [$this->getColumns()] + $this->getData();
    }
}