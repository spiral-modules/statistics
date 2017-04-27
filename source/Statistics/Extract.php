<?php

namespace Spiral\Statistics;

use Spiral\Statistics\Database\Sources\StatisticsSource;
use Spiral\Statistics\Database\Statistics;
use Spiral\Statistics\Exceptions\EmptyExtractEventsException;
use Spiral\Statistics\Extract\Range;
use Spiral\Statistics\Extract\Events;
use Spiral\Statistics\Extract\RangeInterface;

class Extract
{
    /** @var StatisticsSource */
    protected $source;

    /** @var DatetimeConverter */
    protected $converter;

    /**
     * Extract constructor.
     *
     * @param DatetimeConverter $converter
     * @param StatisticsSource  $source
     */
    public function __construct(
        DatetimeConverter $converter,
        StatisticsSource $source
    ) {
        $this->source = $source;
        $this->converter = $converter;
    }

    /**
     * Extract listed events inside given timeframe.
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param RangeInterface     $range
     * @param array              $eventsInput
     * @return Events
     */
    public function events(
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        RangeInterface $range,
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

        $events = new Events($eventsInput);

        $queried = $this->source->findExtract($start, $end, $eventsInput);
        $lastDatetime = clone $start;
        $row = $events->addRow($start->format($range->getFormat()));

        /** @var Statistics $extracted */
        foreach ($queried as $extracted) {
            $row = $this->fillGaps($lastDatetime, $extracted->timestamp, $events, $range) ?? $row;

            $row->addEvent($extracted->name, $extracted->value);
        }

        $this->fillGaps($lastDatetime, $end, $events, $range);

        return $events;
    }

    /**
     * Add blank rows if between passed current datetime and passed end datetime can be placed
     * blank rows without any events occurred. Return result is last filled row if gaps were
     * filled or null.
     *
     * @param \DateTimeInterface|\DateTimeImmutable $current
     * @param \DateTimeInterface                    $end
     * @param Events                                $events
     * @param RangeInterface                        $range
     * @return Events\Row|null
     */
    protected function fillGaps(
        \DateTimeInterface &$current,
        \DateTimeInterface $end,
        Events $events,
        RangeInterface $range
    ) {
        $lastRow = null;
        $lastDatetimeConverted = $this->converter->convert($current, $range);
        $endDatetimeConverted = $this->converter->convert($end, $range);

        if ($lastDatetimeConverted < $endDatetimeConverted) {
            //one of next intervals, need to add blank rows;
            while ($lastDatetimeConverted < $endDatetimeConverted) {
                $current = $current->add($range->getInterval());
                $lastDatetimeConverted = $this->converter->convert($current, $range);

                $lastRow = $events->addRow($current->format($range->getFormat()));
            }
        }

        return $lastRow;
    }
}