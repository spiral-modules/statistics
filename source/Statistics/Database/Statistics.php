<?php

namespace Spiral\Statistics\Database;

use Spiral\Models\Accessors\SqlTimestamp;
use Spiral\ORM\Record;

/**
 * Class Statistics
 *
 * @package Spiral\Statistics\Database
 *
 * @property SqlTimestamp $timestamp
 * @property string       $name
 * @property float        $value
 */
class Statistics extends Record
{
    /**
     * {@inheritdoc}
     */
    const DATABASE = 'statistics';

    /**
     * {@inheritdoc}
     */
    const SCHEMA = [
        'id'        => 'primary',
        'timestamp' => 'datetime',
        'name'      => 'string',
        'value'     => 'float, nullable',
    ];

    /**
     * {@inheritdoc}
     */
    const INDEXES = [
        [self::INDEX, 'timestamp'],
        [self::INDEX, 'name'],
    ];
}