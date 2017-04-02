<?php

namespace Spiral\Statistics\Database\Sources;

use Spiral\Database\Injections\Parameter;
use Spiral\ORM\Entities\RecordSelector;
use Spiral\ORM\Entities\RecordSource;
use Spiral\ORM\ORMInterface;
use Spiral\Statistics\Database\Occurrence;
use Spiral\Statistics\DatetimeConverter;
use Spiral\Statistics\Extract\Range;

class OccurrenceSource extends RecordSource
{
    const RECORD = Occurrence::class;

    /** @var DatetimeConverter */
    private $converter;

    /**
     * OccurrenceSource constructor.
     *
     * @param ORMInterface      $orm
     * @param DatetimeConverter $converter
     */
    public function __construct(ORMInterface $orm, DatetimeConverter $converter)
    {
        parent::__construct(static::RECORD, $orm);

        $this->converter = $converter;
    }

    /**
     * @param \DateTimeInterface $datetime
     * @return Occurrence|null
     */
    public function findByTimestamp(\DateTimeInterface $datetime)
    {
        $entity = $this->findOne(['timestamp' => $datetime]);

        return $entity;
    }

    /**
     * @param \DateTime $datetime
     * @return Occurrence
     */
    public function getByTimestamp(\DateTime $datetime): Occurrence
    {
        $entity = $this->findByTimestamp($datetime);
        if (empty($entity)) {
            $entity = $this->createFromTimestamp($datetime);
        }

        return $entity;
    }

    /**
     * @param \DateTimeInterface $datetime
     * @return Occurrence
     */
    public function createFromTimestamp(\DateTimeInterface $datetime): Occurrence
    {
        $entity = $this->create([
            'timestamp'  => $this->converter->convert($datetime),
            'day_mark'   => $this->converter->convert($datetime, 'day'),
            'week_mark'  => $this->converter->convert($datetime, 'week'),
            'month_mark' => $this->converter->convert($datetime, 'month'),
            'year_mark'  => $this->converter->convert($datetime, 'year'),
        ]);

        return $entity;
    }

    /**
     * @param Range              $range
     * @param \DateTimeInterface $timestamp
     * @param array              $events
     * @return RecordSelector|Occurrence[]
     */
    public function findWithRange(
        Range $range,
        \DateTimeInterface $timestamp,
        array $events = []
    ): RecordSelector
    {
        $selector = $this->find([$range->getField() => $timestamp]);

        if (!empty($events)) {
            $selector->with(
                'events',
                ['where' => ['{@}.name' => ['IN' => new Parameter($events)]]]
            );
        }

        return $selector;
    }

    public function findByGroupedInterval(
        string $periodField,
        \DateTimeInterface $periodValue,
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        array $events = []
    ): RecordSelector
    {
        $selector = $this->find([
            $periodField => $periodValue,
            'timestamp'  => ['between' => [$start, $end]]
        ]);

        if (!empty($events)) {
            $selector->with(
                'events',
                ['where' => ['{@}.name' => ['IN' => new Parameter($events)]]]
            );
        }

        return $selector;
    }
}