<?php

namespace Spiral\Statistics;

use Spiral\Statistics\Database\Event;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\Database\Sources\EventSource;
use Spiral\Statistics\Database\Sources\OccurrenceSource;

class Track
{
    /** @var OccurrenceSource */
    private $occurrenceSource;

    /** @var EventSource */
    private $eventSource;

    /**
     * Track constructor.
     *
     * @param OccurrenceSource $source
     * @param EventSource      $eventSource
     */
    public function __construct(OccurrenceSource $source, EventSource $eventSource)
    {
        $this->occurrenceSource = $source;
        $this->eventSource = $eventSource;
    }

    /**
     * @param string         $name
     * @param float          $value
     * @param \DateTime|null $datetime
     */
    public function event(string $name, float $value, \DateTime $datetime = null)
    {
        $occurrence = $this->occurrenceSource->getByTimestamp($this->datetime($datetime));

        if (!$occurrence->primaryKey()) {
            $this->addEvent($occurrence, $name, $value);
        } else {
            $this->addOrUpdateEvent($occurrence, $name, $value);
        }

        $occurrence->save();
    }

    /**
     * @param array          $events Should be formatted ad "event_name" => "event_value"
     * @param \DateTime|null $datetime
     */
    public function events(array $events, \DateTime $datetime = null)
    {
        if (empty($events)) {
            //nothing to track
            return;
        }

        $occurrence = $this->occurrenceSource->getByTimestamp($this->datetime($datetime));

        if (!$occurrence->primaryKey()) {
            foreach ($events as $name => $value) {
                $this->addEvent($occurrence, $name, floatval($value));
            }
        } else {
            /** @var Event $event */
            foreach ($events as $name => $value) {
                $this->addOrUpdateEvent($occurrence, $name, floatval($value));
            }
        }

        $occurrence->save();
    }

    /**
     * @param \DateTime|null $datetime
     * @return \DateTime
     */
    protected function datetime(\DateTime $datetime = null): \DateTime
    {
        return $datetime ?? new \DateTime('now');
    }

    /**
     * @param Occurrence $occurrence
     * @param string     $name
     * @param float      $value
     */
    protected function addEvent(Occurrence $occurrence, string $name, float $value)
    {
        $event = $this->eventSource->create(compact('name', 'value'));
        $occurrence->events->add($event);
    }

    /**
     * @param Occurrence $occurrence
     * @param string     $name
     * @param float      $value
     */
    protected function addOrUpdateEvent(Occurrence $occurrence, string $name, float $value)
    {
        /** @var Event $event */
        $event = $occurrence->events->matchOne(compact('name'));
        if (empty($event)) {
            $this->addEvent($occurrence, $name, $value);
        } else {
            $event->value += $value;
        }
    }
}