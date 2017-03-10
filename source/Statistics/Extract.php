<?php

namespace Spiral\Statistics;

use Spiral\Statistics\Database\Sources\OccurrenceSource;
use Spiral\Statistics\Exceptions\InvalidExtractException;
use Spiral\Statistics\Extract\ExtractRange;
use Spiral\Statistics\Extract\ExtractResults;

class Extract
{
    const DAILY   = 'day';
    const WEEKLY  = 'week';
    const MONTHLY = 'month';
    const YEARLY  = 'year';

    const RANGES = [self::DAILY, self::WEEKLY, self::MONTHLY, self::YEARLY];

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
     * @param \DateTime $start
     * @param \DateTime $end
     * @param string    $rangeValue
     * @param array     $events
     * @return ExtractResults
     */
    public function events(
        \DateTime $start,
        \DateTime $end,
        string $rangeValue,
        array $events
    ) {
        if (empty($events)) {
            throw new InvalidExtractException('Extract events are not defined, empty array passed.');
        }

        //Swap start and end dates if incorrect
        if ($start > $end) {
            list($start, $end) = [$end, $start];
        }

        $range = new ExtractRange($rangeValue);
        $results = new ExtractResults($events);

        //do-while allows to add events of last interval occurrence.
        do {
            $results->addRow($start->format($range->getFormat()));

            $datetime = $this->converter->convert($start, $rangeValue);

            foreach ($this->source->findByRange($range, $datetime, $events) as $occurrence) {
                foreach ($occurrence->events as $event) {
                    if (!in_array($event->name, $events)) {
                        continue;
                    }

                    $results->addEvent($event->name, $event->value);
                }
            }

            $start = $start->add($range->getInterval());
        } while ($start <= $end);

        return $results;
    }
}