<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\Events;

class GoogleChartDataset extends AbstractDataset
{
    /** @var array */
    private $labels = [];

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
        $data = $this->data;
        array_unshift($data, $this->labels);

        return $data;
    }
}