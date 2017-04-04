<?php

namespace Spiral\Statistics\Extract\Events;

use Spiral\Statistics\Exceptions\InvalidExtractEventException;

class Row
{
    /** @var string */
    private $label;

    /** @var array */
    private $events;

    /** @var array */
    private $records = [];

    /**
     * Row constructor.
     *
     * @param string $label
     * @param array  $events
     */
    public function __construct(string $label, array $events)
    {
        $this->label = $label;
        $this->events = $events;

        $this->fill();
    }

    /**
     * Fill blank data.
     */
    private function fill()
    {
        foreach ($this->events as $event) {
            $this->records[$event] = 0;
        }
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->records;
    }

    /**
     * Add value to event.
     *
     * @param string $event
     * @param float  $value
     */
    public function addEvent(string $event, float $value)
    {
        if (!isset($this->records[$event])) {
            throw new InvalidExtractEventException($event);
        }

        $this->records[$event] += $value;
    }
}