<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\DatasetInterface;
use Spiral\Statistics\Extract\Events\Row;

class ChartDataset implements DatasetInterface
{
    private $raw = [];
    private $data = [];
    private $params = [];
    private $labels = [];

    /**
     * ChartDataset constructor.
     *
     * @param Row[] $data
     * @param array $params
     *
     * params is assoc array, where key is event name and value is an options array for this
     * event dataset like: "label" => "Event Label"
     */
    public function __construct(array $data, array $params = [])
    {
        $this->raw = $data;
        $this->params = $params;

        $this->convert();
    }

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

    public function pack(): array
    {
        return [
            'labels'   => $this->labels,
            'datasets' => array_values($this->data)
        ];
    }
}