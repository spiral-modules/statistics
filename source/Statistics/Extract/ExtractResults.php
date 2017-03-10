<?php

namespace Spiral\Statistics\Extract;

use Spiral\Statistics\Exceptions\InvalidExtractResultsException;

class ExtractResults
{
    /** @var array */
    protected $results = [];

    /** @var null|string */
    protected $label = null;

    /** @var array */
    protected $events = [];

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
     */
    public function addRow(string $label)
    {
        $this->label = $label;

        if (!array_key_exists($label, $this->results)) {
            $this->results[$label] = [];
        }

        foreach ($this->events as $event) {
            $this->results[$label][$event] = 0;
        }
    }

    /**
     * @param string $event
     * @param float  $value
     */
    public function addEvent(string $event, float $value)
    {
        if (empty($this->label)) {
            throw new InvalidExtractResultsException('Results label is not defined, use "addRow" method first.');
        }

        if (!isset($this->results[$this->label][$event])) {
            throw new InvalidExtractResultsException('Unknown event, should be passed in construct.');
        }

        $this->results[$this->label][$event] += $value;
    }

    /**
     * @return array
     */
    public function results(): array
    {
        return $this->results;
    }
}