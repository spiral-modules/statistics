<?php

namespace Spiral\Statistics\Database\Sources;

use Spiral\Database\Builders\Prototypes\AbstractSelect;
use Spiral\Database\Injections\Parameter;
use Spiral\ORM\Entities\RecordSelector;
use Spiral\ORM\Entities\RecordSource;
use Spiral\Statistics\Database\Statistics;

class StatisticsSource extends RecordSource
{
    const RECORD = Statistics::class;

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @param array              $events
     * @return RecordSelector
     */
    public function findExtract(
        \DateTimeInterface $start,
        \DateTimeInterface $end,
        array $events
    ): RecordSelector
    {
        $selector = $this->find()
            ->where('timestamp', 'between', $start, $end);

        if (!empty($events)) {
            $selector->where('name', 'IN', new Parameter($events));
        }

        return $selector->orderBy('timestamp', AbstractSelect::SORT_ASC);
    }

    /**
     * @param string             $name
     * @param \DateTimeInterface $datetime
     * @return null|Statistics
     */
    public function findByEventNameAndDatetime(string $name, \DateTimeInterface $datetime)
    {
        return $this->findOne([
            'name'      => $name,
            'timestamp' => $datetime
        ]);
    }
}