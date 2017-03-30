<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\DatasetInterface;
use Spiral\Statistics\Extract\Events\Row;

class GoogleChartDataset implements DatasetInterface
{
    /** @var array */
    private $labels;

    /** @var array */
    private $data;

    /** @var array|Row[] */
    private $raw;

    /**
     * GoogleChartDataset constructor.
     *
     * @param array $labels
     */
    public function __construct(array $labels)
    {
        $this->labels = $labels;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->raw = $data;
        $this->convert();
    }

    /**
     * @return array
     */
    protected function convert()
    {
        foreach ($this->raw as $row) {
            $label = $row->getLabel();
            $row = array_values($row->getEvents());
            array_unshift($row, $label);

            $this->data[] = $row;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function pack(): array
    {
        return [$this->labels] + $this->data;
    }
}