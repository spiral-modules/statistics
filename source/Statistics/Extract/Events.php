<?php

namespace Spiral\Statistics\Extract;

use Spiral\Statistics\Extract\Events\Row;

class Events
{
    /** @var array */
    protected $events = [];

    /** @var Row[] */
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
     * @return Row[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }
}