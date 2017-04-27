<?php

namespace Spiral\Statistics;

use Spiral\Statistics\Database\Sources\StatisticsSource;
use Spiral\Statistics\Database\Statistics;

class Track
{
    /** @var StatisticsSource */
    private $source;

    /**
     * Track constructor.
     *
     * @param StatisticsSource $statisticsSource
     */
    public function __construct(StatisticsSource $statisticsSource)
    {
        $this->source = $statisticsSource;
    }

    /**
     * @param string                  $name
     * @param float                   $value
     * @param \DateTimeInterface|null $datetime
     */
    public function event(string $name, float $value, \DateTimeInterface $datetime = null)
    {
        $datetime = $this->datetime($datetime);
        $record = $this->source->findByEventNameAndDatetime($name, $datetime);

        if (empty($record)) {
            $this->addEvent($name, $value, $datetime);
        } else {
            $this->updateEvent($record, $value);
        }
    }

    /**
     * @param array                   $events Should be formatted as "event_name" => "event_value"
     * @param \DateTimeInterface|null $datetime
     */
    public function events(array $events, \DateTimeInterface $datetime = null)
    {
        //nothing to track
        if (empty($events)) {
            return;
        }

        foreach ($events as $name => $value) {
            $this->event($name, floatval($value), $datetime);
        }
    }

    /**
     * @param \DateTimeInterface|null $datetime
     * @return \DateTimeInterface
     */
    protected function datetime(\DateTimeInterface $datetime = null): \DateTimeInterface
    {
        return $datetime ?? new \DateTime('now');
    }

    protected function addEvent(string $name, float $value, \DateTimeInterface $datetime)
    {
        /** @var Statistics $record */
        $record = $this->source->create();
        $record->name = $name;
        $record->value = $value;
        $record->timestamp = $datetime;
        $record->save();
    }

    /**
     * @param Statistics $record
     * @param float      $value
     */
    protected function updateEvent(Statistics $record, float $value)
    {
        $record->value += $value;
        $record->save();
    }
}