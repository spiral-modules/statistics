<?php

namespace Spiral\Statistics;

use Spiral\Statistics\Database\Sources\OccurrenceSource;
use Spiral\Statistics\Exceptions\EmptyExtractEventsException;
use Spiral\Statistics\Extract\Range;
use Spiral\Statistics\Extract\Events;

class Extract
{
    /** @var OccurrenceSource */
    protected $source;

    /** @var DatetimeConverter */
    protected $converter;

    /**
     * Extract constructor.
     *
     * @param OccurrenceSource  $source
     * @param DatetimeConverter $converter
     */
    public function __construct(OccurrenceSource $source, DatetimeConverter $converter)
    {
        $this->source = $source;
        $this->converter = $converter;
    }

    /**
     * @param \DateTime          $start
     * @param \DateTimeInterface $end
     * @param string             $rangeInput
     * @param array              $eventsInput
     * @return Events
     */
    public function events(
        \DateTime $start,
        \DateTimeInterface $end,
        string $rangeInput,
        array $eventsInput
    ): Events
    {
        if (empty($eventsInput)) {
            throw new EmptyExtractEventsException();
        }

        $start = $this->converter->immutable($start);
        $end = $this->converter->immutable($end);

        //Swap start and end dates if incorrect
        if ($start > $end) {
            list($start, $end) = [$end, $start];
        }

        $range = new Range($rangeInput);
        $events = new Events($eventsInput);

        /** @var \DateTime $iteratedDatetime */
        $iteratedDatetime = clone $start;

        //do-while allows to add events of last interval occurrence.
        do {
            $row = $events->addRow($iteratedDatetime->format($range->getFormat()));

            $datetime = $this->converter->convert($iteratedDatetime, $rangeInput);

            foreach ($this->source->findByGroupedInterval(
                $range->getField(),
                $datetime,
                $start,
                $end,
                $eventsInput
            ) as $occurrence) {
                foreach ($occurrence->events as $event) {
                    if (!in_array($event->name, $eventsInput)) {
                        continue;
                    }

                    $row->addEvent($event->name, $event->value);
                }
            }

            $iteratedDatetime = $iteratedDatetime->add($range->getInterval());
        } while ($iteratedDatetime <= $end);

        return $events;
    }
}