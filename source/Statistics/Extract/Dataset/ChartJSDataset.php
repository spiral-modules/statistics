<?php

namespace Spiral\Statistics\Extract\Dataset;

use Spiral\Statistics\Extract\Events;

class ChartJSDataset extends AbstractDataset
{
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