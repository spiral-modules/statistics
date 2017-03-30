<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\DatasetInterface;
use Spiral\Statistics\Extract\Events\Row;

class ChartJsDataset implements DatasetInterface
{
    /** @var array|Row[] */
    private $raw = [];

    /** @var array */
    private $data = [];

    /** @var array */
    private $params = [];

    /** @var array */
    private $labels = [];

    /**
     * ChartDataset constructor.
     *
     * @param array $params - assoc array, where key is event name and value is an options array
     *                      for this event dataset like: "label" => "Event Label", etc.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
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
     * Convert to real format.
     */
    protected function convert()
    {
        foreach ($this->raw as $row) {
            $label = $row->getLabel();
            $this->labels[] = $label;

            foreach ($row->getEvents() as $event => $value) {
                if (!array_key_exists($event, $this->data)) {
                    if (array_key_exists($event, $this->params)) {
                        $this->data[$event] = $this->params[$event];
                    }

                    $this->data[$event]['data'] = [];
                }

                $this->data[$event]['data'][] = $value;
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function pack(): array
    {
        return [
            'labels'   => $this->labels,
            'datasets' => array_values($this->data)
        ];
    }
}