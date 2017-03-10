<?php

namespace Spiral\Statistics\Database;

use Spiral\Models\Accessors\SqlTimestamp;
use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\Entities\Relations\HasManyRelation;
use Spiral\ORM\Record;

/**
 * Class Occurrence
 *
 * @property SqlTimestamp $timestamp
 * @property SqlTimestamp $day_mark
 * @property SqlTimestamp $week_mark
 * @property SqlTimestamp $month_mark
 * @property SqlTimestamp $year_mark
 * @@property HasManyRelation $events
 */
class Occurrence extends Record
{
    use TimestampsTrait;

    /**
     * {@inheritdoc}
     */
    const DATABASE = 'statistics';

    /**
     * {@inheritdoc}
     */
    const SCHEMA = [
        'id'         => 'primary',

        //timestamps
        'timestamp'  => 'datetime',
        'day_mark'   => 'datetime',
        'week_mark'  => 'datetime',
        'month_mark' => 'datetime',
        'year_mark'  => 'datetime',

        //All occurred events
        'events'     => [
            self::HAS_MANY => Event::class
        ],
    ];

    /**
     * {@inheritdoc}
     */
    const SECURED = [];

    /**
     * {@inheritdoc}
     */
    const INDEXES = [
        [self::UNIQUE, 'timestamp'],
    ];
}