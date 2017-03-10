<?php

namespace Spiral\Statistics\Database;

use Spiral\Models\Traits\TimestampsTrait;
use Spiral\ORM\Record;

/**
 * Class Event
 *
 * @property string $name
 * @property float  $value
 */
class Event extends Record
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
        'id'    => 'primary',
        'name'  => 'string',
        'value' => 'float, nullable',
    ];

    /**
     * {@inheritdoc}
     */
    const INDEXES = [
        [self::INDEX, 'name'],
    ];

    /**
     * {@inheritdoc}
     */
    const SECURED = [];
}